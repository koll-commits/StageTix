<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\TicketArea;
use App\Models\TicketCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<TicketCategory> */
class TicketCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'tenant_id' => fn (array $attrs) => Event::query()->whereKey($attrs['event_id'])->value('tenant_id'),
            'area_id' => fn (array $attrs) => TicketArea::factory()->create(['event_id' => $attrs['event_id']])->id,
            'name' => 'Tiket '.Str::upper(Str::random(5)),
            'price_amount' => fake()->randomElement([150000, 250000, 500000, 1000000]),
            'quota' => 100,
            'sold_qty' => 0,
            'is_sold_out' => false,
            'is_active' => true,
            'sort_order' => 1,
        ];
    }

    public function soldOut(): static
    {
        return $this->state(['is_sold_out' => true, 'sold_qty' => 100]);
    }
}
