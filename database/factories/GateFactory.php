<?php

namespace Database\Factories;

use App\Models\Gate;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Gate> */
class GateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'venue_id' => Venue::factory(),
            'code' => 'G'.Str::upper(Str::random(3)),
            'name' => 'Gate '.fake()->numberBetween(1, 9),
            'status' => 'active',
        ];
    }
}
