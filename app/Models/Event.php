<?php

namespace App\Models;

use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['venue_id', 'title', 'slug', 'genre', 'description', 'poster_path', 'starts_at', 'ends_at', 'sales_start_at', 'sales_end_at', 'max_tickets_per_account', 'status'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'sales_start_at' => 'datetime',
            'sales_end_at' => 'datetime',
            'max_tickets_per_account' => 'integer',
        ];
    }

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return BelongsTo<Venue, $this> */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /** @return HasMany<TicketArea, $this> */
    public function areas(): HasMany
    {
        return $this->hasMany(TicketArea::class);
    }

    /** @return HasMany<TicketCategory, $this> */
    public function ticketCategories(): HasMany
    {
        return $this->hasMany(TicketCategory::class);
    }

    /** @return HasMany<EventStaffAssignment, $this> */
    public function staffAssignments(): HasMany
    {
        return $this->hasMany(EventStaffAssignment::class);
    }

    /** @param Builder<Event> $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published');
    }
}
