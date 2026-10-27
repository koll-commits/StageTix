<?php

namespace App\Models;

use Database\Factories\PromoCodeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoCode extends Model
{
    /** @use HasFactory<PromoCodeFactory> */
    use HasFactory;

    protected $fillable = ['code', 'discount_type', 'discount_value', 'max_discount_amount', 'usage_limit', 'used_count', 'per_user_limit', 'valid_from', 'valid_to', 'is_active'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'discount_value' => 'integer',
            'max_discount_amount' => 'integer',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'per_user_limit' => 'integer',
            'valid_from' => 'datetime',
            'valid_to' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
