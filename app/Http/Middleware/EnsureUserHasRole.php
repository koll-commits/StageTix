<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enforcement server-side per konteks (admin | eo | buyer).
 * Menu tersembunyi bukan authorization: URL yang diketik langsung tetap ditolak 403.
 */
final class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // Tamu ditangani middleware `auth`; di sini fokus pada role & status akun.
        if ($user === null || ! $user->hasRole($role)) {
            abort(403);
        }

        return $next($request);
    }
}
