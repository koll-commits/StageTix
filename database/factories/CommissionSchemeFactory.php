<?php

namespace Database\Factories;

use App\Models\CommissionScheme;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<CommissionScheme> */
class CommissionSchemeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'commission_rate' => 0.1000,
            'valid_from' => Carbon::create(2026, 1, 1, 0, 0, 0, 'UTC'),
            'valid_to' => null,
        ];
    }
}
