<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('slug', 100)->unique();
            $table->string('display_name', 120);
            $table->string('status', 20)->default('pending'); // pending|active|suspended|inactive
            $table->timestamps(6);
            $table->softDeletes('deleted_at', 6);
        });

        Schema::create('tenant_balances', function (Blueprint $table): void {
            $table->foreignId('tenant_id')->primary()->constrained('tenants')->cascadeOnDelete();
            $table->unsignedBigInteger('available_amount')->default(0);
            $table->unsignedBigInteger('held_amount')->default(0); // saldo tertahan (penarikan dana, FR-EO-07)
            $table->timestamps(6);
        });
        DB::statement('ALTER TABLE tenant_balances ADD CONSTRAINT chk_tenant_balance_nonneg CHECK (available_amount >= 0 AND held_amount >= 0)');

        Schema::create('tenant_bank_accounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('account_number_cipher'); // terenkripsi (cast encrypted)
            $table->string('account_last4', 4);
            $table->string('bank_code', 20);
            $table->string('account_holder', 120);
            $table->string('status', 20)->default('unverified'); // unverified|verified|rejected
            $table->boolean('is_primary')->default(false);
            $table->timestamps(6);

            $table->unique(['tenant_id', 'id']); // target composite FK withdrawals
        });

        // tambahan kolom untuk login dengan telepon
        Schema::table('users', function (Blueprint $table): void {
            $table->string('phone_e164', 20)->nullable()->unique()->after('email');
        });

        // peran tingkat platform
        Schema::create('user_platform_roles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 30); // admin|finance
            $table->timestamps(6);

            $table->unique(['user_id', 'role']);
        });

        // peran user pada EO
        Schema::create('user_tenant_roles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('role', 30); // owner|operator|gate_staff
            $table->timestamps(6);

            $table->unique(['user_id', 'tenant_id', 'role']);
            $table->index(['tenant_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_tenant_roles');
        Schema::dropIfExists('user_platform_roles');
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['phone_e164']);
            $table->dropColumn('phone_e164');
        });
        Schema::dropIfExists('tenant_bank_accounts');
        Schema::dropIfExists('tenant_balances');
        Schema::dropIfExists('tenants');
    }
};
