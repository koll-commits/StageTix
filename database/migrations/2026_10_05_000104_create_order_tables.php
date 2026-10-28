<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->uuid('public_id');
            $table->string('order_number', 30);
            $table->foreignId('user_id')->constrained()->restrictOnDelete(); // pembeli wajib login
            $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->nullOnDelete();
            $table->string('checkout_key', 100); 
            $table->binary('tracking_token_hash', 32); 
            $table->string('status', 30)->default('draft'); 
            $table->unsignedBigInteger('subtotal_amount')->default(0);
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('tax_amount')->default(0);
            $table->unsignedBigInteger('service_fee_amount')->default(0);
            $table->unsignedBigInteger('grand_total_amount')->default(0);
            // snapshot tarif saat checkout
            $table->decimal('tax_rate_snapshot', 6, 4)->default(0);
            $table->decimal('service_fee_rate_snapshot', 6, 4)->default(0);
            $table->json('promo_snapshot')->nullable();
            $table->json('buyer_snapshot')->nullable();
            $table->dateTime('payment_expires_at', 6)->nullable();
            $table->dateTime('placed_at', 6)->nullable();
            $table->timestamps(6);

            $table->unique('public_id');
            $table->unique('order_number');
            $table->unique('checkout_key');
            $table->unique('tracking_token_hash');
            $table->index(['user_id', 'status']);
            $table->index(['status', 'payment_expires_at']); 
        });

        Schema::create('event_orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->unsignedBigInteger('commission_id');
            $table->string('status', 30)->default('pending'); // pending|paid|completed|cancelled
            $table->decimal('commission_rate_snapshot', 6, 4);
            $table->unsignedBigInteger('subtotal_amount');
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('tax_amount')->default(0);
            $table->unsignedBigInteger('service_fee_amount')->default(0);
            $table->unsignedBigInteger('commission_amount');
            $table->unsignedBigInteger('net_amount');
            $table->timestamps(6);

            $table->unique(['order_id', 'tenant_id']); 
            $table->unique(['tenant_id', 'id']); 
            $table->index(['tenant_id', 'status']);
            $table->foreign(['tenant_id', 'commission_id'])
                ->references(['tenant_id', 'id'])->on('commission_schemes')->restrictOnDelete();
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->unsignedBigInteger('event_order_id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('ticket_category_id');
            $table->string('event_title_snapshot', 150);
            $table->string('category_name_snapshot', 120);
            $table->string('area_name_snapshot', 80);
            $table->unsignedBigInteger('unit_price_snapshot');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('line_total');
            $table->timestamps(6);

            $table->unique(['tenant_id', 'id']);
            $table->index(['tenant_id', 'event_order_id']);
            $table->index(['tenant_id', 'event_id']);
            $table->foreign(['tenant_id', 'event_order_id'])
                ->references(['tenant_id', 'id'])->on('event_orders')->cascadeOnDelete();
            $table->foreign(['tenant_id', 'event_id'])
                ->references(['tenant_id', 'id'])->on('events')->restrictOnDelete();
            $table->foreign(['tenant_id', 'ticket_category_id'])
                ->references(['tenant_id', 'id'])->on('ticket_categories')->restrictOnDelete();
        });
        DB::statement('ALTER TABLE order_items ADD CONSTRAINT chk_order_item_qty CHECK (quantity > 0)');

        // hold terhubung ke event_order
        Schema::table('area_holds', function (Blueprint $table): void {
            $table->unsignedBigInteger('event_order_id')->nullable()->after('status');
            $table->foreign(['tenant_id', 'event_order_id'])
                ->references(['tenant_id', 'id'])->on('event_orders')->restrictOnDelete();
        });

        // qr_code: token acak, unik global
        Schema::create('tickets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('order_item_id');
            $table->foreignId('holder_user_id')->constrained('users')->restrictOnDelete();
            $table->string('holder_name', 120);
            $table->string('qr_code', 64);
            $table->string('status', 20)->default('issued'); 
            $table->dateTime('issued_at', 6);
            $table->dateTime('checked_in_at', 6)->nullable();
            $table->timestamps(6);

            $table->unique('qr_code');
            $table->unique(['tenant_id', 'id']); 
            $table->index(['tenant_id', 'event_id', 'status']);
            $table->index(['holder_user_id', 'status']);
            $table->foreign(['tenant_id', 'event_id'])
                ->references(['tenant_id', 'id'])->on('events')->restrictOnDelete();
            $table->foreign(['tenant_id', 'order_item_id'])
                ->references(['tenant_id', 'id'])->on('order_items')->restrictOnDelete();
        });

        // satu tiket hanya bisa check-in sekali
        Schema::create('ticket_checkins', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->unsignedBigInteger('ticket_id');
            $table->foreignId('gate_id')->constrained('gates')->restrictOnDelete();
            $table->foreignId('scanned_by')->constrained('users')->restrictOnDelete();
            $table->dateTime('scanned_at', 6);
            $table->timestamps(6);

            $table->unique('ticket_id');
            $table->index(['tenant_id', 'scanned_at']);
            $table->foreign(['tenant_id', 'ticket_id'])
                ->references(['tenant_id', 'id'])->on('tickets')->restrictOnDelete();
        });

        // riwayat perubahan kuota
        Schema::create('ticket_inventory_movements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->unsignedBigInteger('ticket_category_id');
            $table->foreignId('area_hold_id')->nullable()->constrained('area_holds')->nullOnDelete();
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->string('idempotency_key', 100);
            $table->string('type', 20); // hold|release|sale|adjustment
            $table->integer('quantity_delta');
            $table->timestamps(6);

            $table->unique('idempotency_key');
            $table->index(['tenant_id', 'ticket_category_id']);
            $table->foreign(['tenant_id', 'ticket_category_id'])
                ->references(['tenant_id', 'id'])->on('ticket_categories')->restrictOnDelete();
        });
    }
    
     /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('ticket_inventory_movements');
        Schema::dropIfExists('ticket_checkins');
        Schema::dropIfExists('tickets');
        Schema::table('area_holds', function (Blueprint $table): void {
            $table->dropForeign(['tenant_id', 'event_order_id']);
            $table->dropColumn('event_order_id');
        });
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('event_orders');
        Schema::dropIfExists('orders');
    }
};
