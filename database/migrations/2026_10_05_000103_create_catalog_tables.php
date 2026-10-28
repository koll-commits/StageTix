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
        Schema::create('commission_schemes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->decimal('commission_rate', 6, 4); 
            $table->dateTime('valid_from', 6);
            $table->dateTime('valid_to', 6)->nullable(); 
            $table->timestamps(6);

            $table->unique(['tenant_id', 'id']); 
            $table->index(['tenant_id', 'valid_from']);
        });
        DB::statement('ALTER TABLE commission_schemes ADD CONSTRAINT chk_commission_rate CHECK (commission_rate >= 0 AND commission_rate <= 1)');
        DB::statement('ALTER TABLE commission_schemes ADD CONSTRAINT chk_commission_period CHECK (valid_to IS NULL OR valid_to > valid_from)');

        Schema::create('events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->foreignId('venue_id')->constrained()->restrictOnDelete();
            $table->string('title', 150);
            $table->string('slug', 160)->unique(); 
            $table->string('genre', 40);
            $table->text('description')->nullable();
            $table->string('poster_path', 255)->nullable();
            $table->dateTime('starts_at', 6);
            $table->dateTime('ends_at', 6)->nullable();
            $table->dateTime('sales_start_at', 6)->nullable();
            $table->dateTime('sales_end_at', 6)->nullable();
            $table->unsignedSmallInteger('max_tickets_per_account')->default(4); 
            $table->string('status', 20)->default('draft'); 
            $table->timestamps(6);
            $table->softDeletes('deleted_at', 6);

            $table->unique(['tenant_id', 'id']); 
            $table->index(['tenant_id', 'status', 'starts_at']); 
            $table->index(['status', 'starts_at']); 
            $table->index(['genre', 'starts_at']);
        });
        DB::statement('ALTER TABLE events ADD CONSTRAINT chk_event_period CHECK (ends_at IS NULL OR ends_at > starts_at)');

        Schema::create('ticket_areas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->unsignedBigInteger('event_id');
            $table->foreignId('venue_area_template_id')->nullable()->constrained('venue_area_templates')->nullOnDelete();
            $table->string('name', 80);
            $table->unsignedInteger('capacity');
            $table->timestamps(6);

            $table->unique(['tenant_id', 'id']);
            $table->unique(['tenant_id', 'event_id', 'id']); 
            $table->unique(['tenant_id', 'event_id', 'name']);
            // event harus milik EO yang sama
            $table->foreign(['tenant_id', 'event_id'])
                ->references(['tenant_id', 'id'])->on('events')->restrictOnDelete();
        });

        Schema::create('ticket_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('area_id');
            $table->string('name', 120);
            $table->unsignedBigInteger('price_amount'); // Rupiah (NFR-DAT-09)
            $table->unsignedInteger('quota');
            $table->unsignedInteger('sold_qty')->default(0);
            $table->boolean('is_sold_out')->default(false); // sakelar cepat FR-EO-03
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->dateTime('sales_start_at', 6)->nullable();
            $table->dateTime('sales_end_at', 6)->nullable();
            $table->timestamps(6);

            $table->unique(['tenant_id', 'id']); // target composite FK (order_items, area_holds)
            $table->unique(['tenant_id', 'event_id', 'name']);
            $table->index(['tenant_id', 'event_id', 'is_active']);
            $table->foreign(['tenant_id', 'event_id'])
                ->references(['tenant_id', 'id'])->on('events')->restrictOnDelete();
            // area harus milik event dan EO yang sama
            $table->foreign(['tenant_id', 'event_id', 'area_id'])
                ->references(['tenant_id', 'event_id', 'id'])->on('ticket_areas')->restrictOnDelete();
        });
        DB::statement('ALTER TABLE ticket_categories ADD CONSTRAINT chk_ticket_sold_le_quota CHECK (sold_qty <= quota)');

        Schema::create('promo_codes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->restrictOnDelete(); // null = promo platform
            $table->string('code', 40)->unique();
            $table->string('discount_type', 10); // percent|fixed
            $table->unsignedBigInteger('discount_value'); // percent: 1..100, fixed: Rupiah
            $table->unsignedBigInteger('max_discount_amount')->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedSmallInteger('per_user_limit')->default(1);
            $table->dateTime('valid_from', 6);
            $table->dateTime('valid_to', 6)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps(6);

            $table->index(['tenant_id', 'is_active']);
        });
        DB::statement("ALTER TABLE promo_codes ADD CONSTRAINT chk_promo_type CHECK (discount_type IN ('percent','fixed') AND (discount_type = 'fixed' OR discount_value <= 100))");

        // penugasan staf gate ke event
        Schema::create('event_staff_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('event_id');
            $table->foreignId('gate_id')->nullable()->constrained('gates')->nullOnDelete();
            // diisi user_id saat aktif, NULL saat selesai (satu event aktif per staf)
            $table->unsignedBigInteger('active_user_lock')->nullable();
            $table->timestamps(6);

            $table->unique(['user_id', 'event_id']);
            $table->unique('active_user_lock');
            $table->index(['tenant_id', 'event_id']);
            $table->foreign(['tenant_id', 'event_id'])
                ->references(['tenant_id', 'id'])->on('events')->cascadeOnDelete();
        });

        // hold kuota sementara
        Schema::create('area_holds', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->unsignedBigInteger('ticket_category_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('hold_key', 100);
            $table->unsignedSmallInteger('quantity');
            $table->string('status', 20)->default('active'); 
            $table->dateTime('expires_at', 6);
            $table->timestamps(6);

            $table->unique('hold_key');
            $table->unique(['tenant_id', 'id']);
            $table->index(['status', 'expires_at']); 
            $table->index(['tenant_id', 'ticket_category_id', 'status']);
            $table->foreign(['tenant_id', 'ticket_category_id'])
                ->references(['tenant_id', 'id'])->on('ticket_categories')->restrictOnDelete();
        });
        DB::statement('ALTER TABLE area_holds ADD CONSTRAINT chk_hold_qty CHECK (quantity > 0)');
    }

     /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('area_holds');
        Schema::dropIfExists('event_staff_assignments');
        Schema::dropIfExists('promo_codes');
        Schema::dropIfExists('ticket_categories');
        Schema::dropIfExists('ticket_areas');
        Schema::dropIfExists('events');
        Schema::dropIfExists('commission_schemes');
    }
};
