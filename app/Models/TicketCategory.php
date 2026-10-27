<?php

namespace App\Models;

use Database\Factories\TicketCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketCategory extends Model
{
    /** @use HasFactory<TicketCategoryFactory> */
    use HasFactory;

    protected $fillable = ['event_id', 'area_id', 'name', 'price_amount', 'quota', 'sold_qty', 'is_sold_out', 'is_active', 'sort_order', 'sales_start_at', 'sales_end_at'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'price_amount' => 'integer',
            'quota' => 'integer',
            'sold_qty' => 'integer',
            'is_sold_out' => 'boolean',
            'is_active' => 'boolean',
            'sales_start_at' => 'datetime',
            'sales_end_at' => 'datetime',
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

    /** @return BelongsTo<TicketArea, $this> */
    public function area(): BelongsTo
    {
        return $this->belongsTo(TicketArea::class, 'area_id');
    }

    /** @return HasMany<AreaHold, $this> */
    public function holds(): HasMany
    {
        return $this->hasMany(AreaHold::class);
    }

    /** Sisa kuota sebelum dikurangi area hold aktif (hold dihitung di service dengan row lock — ADR-07). */
    public function remainingBeforeHolds(): int
    {
        return max(0, $this->quota - $this->sold_qty);
    }
}
