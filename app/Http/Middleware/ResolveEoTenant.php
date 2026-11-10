<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membaca slug EO dari URL /eo/{eo}, memastikan user adalah anggotanya,
 * lalu menyimpan tenant ke request. Pakai ":manage" untuk halaman khusus owner/operator.
 */
class ResolveEoTenant
{
    public function handle(Request $request, Closure $next, ?string $level = null): Response
    {
        $tenant = Tenant::where('slug', (string) $request->route('eo'))->first();
        $role = $tenant
            ? $request->user()->tenantRoles()->where('eo_id', $tenant->id)->value('role')
            : null;

        abort_unless($tenant && $role, 404);
        abort_unless($tenant->isActive(), 403, 'Akun Event Organizer sedang tidak aktif.');
        abort_if($level === 'manage' && $role === 'gate_staff', 403);

        $request->attributes->set('tenant', $tenant);
        $request->route()->forgetParameter('eo');
        URL::defaults(['eo' => $tenant->slug]);
        View::share(['tenant' => $tenant, 'eoRole' => $role]);

        return $next($request);
    }
}
