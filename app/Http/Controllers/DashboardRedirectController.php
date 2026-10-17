<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    /**
     * Arahkan pengguna ke portal sesuai role.
     * Slug tenant masih placeholder 'demo' sampai model Event Organizer tersedia.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        return match (true) {
            $user->isAdmin() => redirect()->route('admin.dashboard'),
            $user->isEventOrganizer() => redirect()->route('eo.dashboard', ['tenant' => 'demo']),
            $user->isBuyer() => redirect()->route('buyer.orders.index'),
            default => abort(403),
        };
    }
}
