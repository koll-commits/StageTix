<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\TicketArea;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<TicketArea> */
class TicketAreaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'tenant_id' => fn (array $attrs) => Event::query()->whereKey($attrs['event_id'])->value('tenant_id'),
            'name' => 'Zona '.Str::upper(Str::random(5)),
            'capacity' => 500,
        ];
    }
}
