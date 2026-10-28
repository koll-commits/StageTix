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
        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete(); 
            $table->string('payment_reference', 100);
            $table->string('idempotency_key', 100);
            $table->unsignedBigInteger('amount');
            $table->string('status', 30)->default('created'); 
            $table->dateTime('expires_at', 6)->nullable();
            $table->dateTime('settled_at', 6)->nullable();
            $table->timestamps(6);

            $table->unique('order_id');
            $table->unique('payment_reference');
            $table->unique('idempotency_key');
        });

        Schema::create('payment_attempts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->string('provider_reference', 120);
            $table->text('qris_payload')->nullable(); 
            $table->string('status', 30)->default('created'); 
            $table->dateTime('expires_at', 6)->nullable();
            $table->timestamps(6);

            $table->unique('provider_reference');
            $table->index(['payment_id', 'status']);
        });

        // callback gateway, hanya boleh ditambah
        Schema::create('payment_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('payment_id')->constrained()->restrictOnDelete();
            $table->foreignId('payment_attempt_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider_event_id', 150);
            $table->string('signature', 255)->nullable();
            $table->json('payload');
            $table->string('result', 30); 
            $table->dateTime('received_at', 6);
            $table->timestamps(6);

            $table->unique('provider_event_id'); 
            $table->index(['payment_id', 'result']);
        });

        Schema::create('withdrawals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->unsignedBigInteger('bank_account_id');
            $table->foreignId('requested_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('idempotency_key', 100);
            $table->unsignedBigInteger('amount');
            $table->string('status', 30)->default('pending'); 
            $table->json('transfer_snapshot')->nullable(); 
    
            $table->unsignedBigInteger('active_tenant_lock')->nullable();
            $table->timestamps(6);

            $table->unique('idempotency_key');
            $table->unique('active_tenant_lock');
            $table->index(['tenant_id', 'status']);
            // rekening harus milik EO yang sama
            $table->foreign(['tenant_id', 'bank_account_id'])
                ->references(['tenant_id', 'id'])->on('tenant_bank_accounts')->restrictOnDelete();
        });
        DB::statement('ALTER TABLE withdrawals ADD CONSTRAINT chk_withdrawal_amount CHECK (amount > 0)');

        // buku besar, hanya boleh ditambah
        Schema::create('ledger_entries', function (Blueprint $table): void {
            $table->id();
            $table->string('account', 20); // tenant|platform
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->restrictOnDelete(); 
            $table->foreignId('order_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('event_order_id')->nullable()->constrained('event_orders')->restrictOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('withdrawal_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('reversal_of_id')->nullable()->constrained('ledger_entries')->restrictOnDelete();
            $table->string('idempotency_key', 100);
            $table->string('type', 40); 
            $table->bigInteger('available_delta')->default(0);
            $table->bigInteger('held_delta')->default(0);
            $table->timestamps(6);

            $table->unique('idempotency_key');
            $table->index(['tenant_id', 'type']);
            $table->index(['account', 'type']);
            $table->index('payment_id');
        });
        DB::statement("ALTER TABLE ledger_entries ADD CONSTRAINT chk_ledger_account CHECK ((account = 'tenant' AND tenant_id IS NOT NULL) OR account = 'platform')");
    }

        /**
        * Reverse the migrations.
        */

    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('payment_events');
        Schema::dropIfExists('payment_attempts');
        Schema::dropIfExists('payments');
    }
};
