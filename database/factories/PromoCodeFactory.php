<?php

namespace Database\Factories;

use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/** @extends Factory<PromoCode> */
class PromoCodeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tenant_id' => null, // promo platform
            'code' => strtoupper(Str::random(8)),
            'discount_type' => 'percent',
            'discount_value' => 10,
            'max_discount_amount' => 50000,
            'usage_limit' => 100,
            'used_count' => 0,
            'per_user_limit' => 1,
            'valid_from' => Carbon::create(2026, 1, 1, 0, 0, 0, 'UTC'),
            'valid_to' => Carbon::create(2027, 12, 31, 23, 59, 59, 'UTC'),
            'is_active' => true,
        ];
    }
}
