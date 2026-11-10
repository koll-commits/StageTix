<?php

namespace App\Policies;

use App\Models\Eo;
use App\Models\User;
use App\Models\UserEoRole;

/**
 * Keputusan administratif dipusatkan di policy (bukan string role tersebar).
 * Aktor harus mengelola eo milik user terkait.
 */
class EoPolicy
{
    private function managesEo(User $user, int $eoId): bool
    {
        return UserEoRole::query()
            ->where('user_id', $user->id)
            ->where('eo_id', $eoId)
            ->whereIn('role', ['owner', 'manager', 'finance'])
            ->exists();
    }

    public function viewAny(User $user): bool
    {
        return UserEoRole::query()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return UserEoRole::query()
            ->where('user_id', $user->id)
            ->whereIn('role', ['owner', 'manager'])
            ->exists();
    }

    public function viewAny(User $user): bool
    {
        return UserEoRole::query()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return UserEoRole::query()
            ->where('user_id', $user->id)
            ->whereIn('role', ['owner', 'manager'])
            ->exists();
    }

    public function update(User $user, Eo $eo): bool
    {
        return $this->managesEo($user, $eo->id);
    }

    public function scheduleCommission(User $user, Eo $eo): bool
    {
        return $this->managesEo($user, $eo->id);
    }

    public function manageBank(User $user, Eo $eo): bool
    {
        return $this->managesEo($user, $eo->id);
    }

    public function assignRole(User $user, Eo $eo): bool
    {
        return $this->managesEo($user, $eo->id);
    }
}
