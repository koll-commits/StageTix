<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $role
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 */
#[Fillable(['name', 'email', 'password', 'role', 'status'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $hidden = [ 'password', 'remember_token', ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Apakah user memegang role tertentu. Kontrak role interim Modul 2
     * (kolom `role`); diformalkan menjadi relasi pada Modul 4–5.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role && $this->isActive();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isEoOperator(): bool
    {
        return $this->hasRole('eo');
    }

    public function isTenantOperator(): bool
    {
        return $this->isEoOperator();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
