<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pertemuan 2: role & status interim pada users.
 * Role: admin (pengelola platform) | eo (staf Event Organizer) | buyer (pembeli).
 * tenant_id sengaja BELUM ditambahkan; isolasi tenant diperkenalkan bersama model tenant.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('buyer')->after('email');
            $table->string('status')->default('active')->after('role'); // active | suspended
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['role', 'status']);
        });
    }
};
