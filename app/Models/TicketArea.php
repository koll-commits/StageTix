<?php

namespace App\Models;

use Database\Factories\TicketAreaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketArea extends Model
{
    /** @use HasFactory<TicketAreaFactory> */
    use HasFactory;

    protected $fillable = ['event_id', 'venue_area_template_id', 'name', 'capacity'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
        ];
    }

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return BelongsTo<Event, $this> */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /** @return BelongsTo<VenueAreaTemplate, $this> */
    public function template(): BelongsTo
    {
        return $this->belongsTo(VenueAreaTemplate::class, 'venue_area_template_id');
    }

    /** @return HasMany<TicketCategory, $this> */
    public function ticketCategories(): HasMany
    {
        return $this->hasMany(TicketCategory::class, 'area_id');
    }
}
