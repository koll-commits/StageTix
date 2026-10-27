<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Sub-pesanan per EO. Snapshot komisi/harga dibuat service saat checkout.
 */
class EventOrder extends Model
{
    // Dibuat oleh service melalui forceFill; tidak ada mass assignment dari request.
    protected $fillable = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'commission_rate_snapshot' => 'decimal:4',
            'subtotal_amount' => 'integer',
            'discount_amount' => 'integer',
            'tax_amount' => 'integer',
            'service_fee_amount' => 'integer',
            'commission_amount' => 'integer',
            'net_amount' => 'integer',
        ];
    }

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<CommissionScheme, $this> */
    public function commission(): BelongsTo
    {
        return $this->belongsTo(CommissionScheme::class, 'commission_id');
    }

    /** @return HasMany<OrderItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
