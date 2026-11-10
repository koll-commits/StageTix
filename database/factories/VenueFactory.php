<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Venue> */
class VenueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Hall '.Str::upper(Str::random(8)),
            'city' => fake()->randomElement(['Jakarta', 'Surabaya', 'Bandung', 'Banyuwangi', 'Yogyakarta']),
            'address' => fake()->streetAddress(),
            'capacity' => fake()->numberBetween(500, 20000),
            'layout_type' => 'hall',
            'status' => 'active',
        ];
    }
}
