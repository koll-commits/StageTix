<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Order induk — platform-scoped (TANPA tenant_id): satu checkout bisa lintas EO (ADR-04).
 */
class Order extends Model
{
    // Dibuat oleh service melalui forceFill; tidak ada mass assignment dari request.
    protected $fillable = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'public_id' => 'string',
            'subtotal_amount' => 'integer',
            'discount_amount' => 'integer',
            'tax_amount' => 'integer',
            'service_fee_amount' => 'integer',
            'grand_total_amount' => 'integer',
            'promo_snapshot' => 'array',
            'buyer_snapshot' => 'array',
            'payment_expires_at' => 'datetime',
            'placed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<PromoCode, $this> */
    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    /** @return HasMany<EventOrder, $this> */
    public function eventOrders(): HasMany
    {
        return $this->hasMany(EventOrder::class);
    }

    /** @return HasOne<Payment, $this> */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
