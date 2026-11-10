<?php

namespace App\Support;

use App\Models\User;

/** Menentukan halaman awal user sesuai role-nya. */
final class HomeRedirect
{
    public static function url(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'eo' => self::eoUrl($user),
            default => route('catalog.home'),
        };
    }

    private static function eoUrl(User $user): string
    {
        $membership = $user->tenantRoles()->with('tenant')->orderBy('id')->first();

        if (! $membership?->tenant) {
            return route('catalog.home');
        }

        $route = $membership->role === 'gate_staff' ? 'eo.checkin' : 'eo.dashboard';

        return route($route, ['eo' => $membership->tenant->slug]);
    }
}
