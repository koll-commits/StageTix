<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        // Guest ditangani middleware auth; di sini fokus pada role/status.
        if ($user === null || ! $user->hasRole(trim($role))) {
            abort(403);
        }

        return $next($request);
    }
}
