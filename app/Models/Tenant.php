<?php

namespace App\Models;

use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Tenant = Event Organizer (EO). eo_id pada model anak di-set eksplisit (bukan dari request).
 */
class Tenant extends Model
{
    /** @use HasFactory<TenantFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'slug', 'display_name', 'status'];

    /** @return HasOne<TenantBalance, $this> */
    public function balance(): HasOne
    {
        return $this->hasOne(TenantBalance::class, 'eo_id');
    }

    /** @return HasMany<TenantBankAccount, $this> */
    public function bankAccounts(): HasMany
    {
        return $this->hasMany(TenantBankAccount::class, 'eo_id');
    }

    /** @return HasMany<Event, $this> */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'eo_id');
    }

    /** @return HasMany<CommissionScheme, $this> */
    public function commissionSchemes(): HasMany
    {
        return $this->hasMany(CommissionScheme::class, 'eo_id');
    }

    /** @return HasMany<UserTenantRole, $this> */
    public function roles(): HasMany
    {
        return $this->hasMany(UserTenantRole::class, 'eo_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
