<?php

namespace App\Models;

use Database\Factories\VenueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    /** @use HasFactory<VenueFactory> */
    use HasFactory;

    protected $fillable = ['name', 'city', 'address', 'capacity', 'layout_type', 'status'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
        ];
    }

    /** @return HasMany<Gate, $this> */
    public function gates(): HasMany
    {
        return $this->hasMany(Gate::class);
    }

    /** @return HasMany<VenueAreaTemplate, $this> */
    public function areaTemplates(): HasMany
    {
        return $this->hasMany(VenueAreaTemplate::class);
    }

    /** @return HasMany<Event, $this> */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
