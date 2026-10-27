<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\CommissionSchemeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Versi komisi bertanggal berlaku (FR-ADM-01). Berlaku ke depan; transaksi lama memakai snapshot.
 */
class CommissionScheme extends Model
{
    /** @use HasFactory<CommissionSchemeFactory> */
    use HasFactory;

    protected $fillable = ['commission_rate', 'valid_from', 'valid_to'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:4',
            'valid_from' => 'datetime',
            'valid_to' => 'datetime',
        ];
    }

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @param Builder<CommissionScheme> $query */
    public function scopeActiveAt(Builder $query, CarbonInterface $at): void
    {
        $query->where('valid_from', '<=', $at)
            ->where(fn (Builder $q) => $q->whereNull('valid_to')->orWhere('valid_to', '>', $at));
    }
}
