<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Tenant> */
class TenantFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'code' => strtoupper(Str::random(6)),
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(4)),
            'display_name' => Str::title($name).' Entertainment',
            'status' => 'active',
        ];
    }

    public function suspended(): static
    {
        return $this->state(['status' => 'suspended']);
    }
}
