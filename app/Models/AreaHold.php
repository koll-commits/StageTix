<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Penguncian kuota sementara selama checkout (FR-BUY-05). Dibuat oleh service (forceFill), bukan dari request.
 */
class AreaHold extends Model
{
    // Dibuat oleh service melalui forceFill; tidak ada mass assignment dari request.
    protected $fillable = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<TicketCategory, $this> */
    public function ticketCategory(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    /** @return BelongsTo<EventOrder, $this> */
    public function eventOrder(): BelongsTo
    {
        return $this->belongsTo(EventOrder::class);
    }
}
