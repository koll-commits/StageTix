<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 150);
            $table->string('city', 80);
            $table->string('address', 255)->nullable();
            $table->unsignedInteger('capacity');
            $table->string('layout_type', 30)->default('hall'); // hall|outdoor|stadium|arena
            $table->string('status', 20)->default('active'); // active|inactive
            $table->timestamps(6);

            $table->unique(['name', 'city']);
            $table->index(['city', 'status']); // filter katalog per kota (FR-BUY-01)
        });

        Schema::create('gates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('code', 20);
            $table->string('name', 80);
            $table->string('status', 20)->default('active');
            $table->timestamps(6);

            $table->unique(['venue_id', 'code']);
        });

        // template zona area
        Schema::create('venue_area_templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);
            $table->unsignedInteger('capacity');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps(6);

            $table->unique(['venue_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venue_area_templates');
        Schema::dropIfExists('gates');
        Schema::dropIfExists('venues');
    }
};
