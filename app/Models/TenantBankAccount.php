<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantBankAccount extends Model
{
    protected $fillable = ['account_number_cipher', 'account_last4', 'bank_code', 'account_holder', 'status', 'is_primary'];
    protected $hidden = ['account_number_cipher'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'account_number_cipher' => 'encrypted',
            'is_primary' => 'boolean',
        ];
    }

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return BelongsTo<User, $this> */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
