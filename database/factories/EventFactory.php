<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Tenant;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/** @extends Factory<Event> */
class EventFactory extends Factory
{
    public function definition(): array
    {
        $title = Str::title(fake()->unique()->words(3, true)).' Live';
        $start = Carbon::create(2027, 1, 15, 19, 0, 0, 'UTC');

        return [
            'tenant_id' => Tenant::factory(),
            'venue_id' => Venue::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(4)),
            'genre' => fake()->randomElement(['Pop', 'Rock', 'Jazz', 'EDM', 'Dangdut']),
            'description' => fake()->paragraph(),
            'starts_at' => $start,
            'ends_at' => $start->copy()->addHours(5),
            'sales_start_at' => $start->copy()->subMonths(2),
            'sales_end_at' => $start->copy()->subHour(),
            'max_tickets_per_account' => 4,
            'status' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => 'draft']);
    }
}
