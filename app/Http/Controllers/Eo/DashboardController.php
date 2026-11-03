<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\EventOrder;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $tenant = $request->attributes->get('tenant');

        $categories = TicketCategory::where('tenant_id', $tenant->id);

        return view('eo.dashboard', [
            'activeEvents' => $tenant->events()->whereIn('status', ['published', 'ongoing'])->count(),
            'soldTotal' => (int) (clone $categories)->sum('sold_qty'),
            'quotaTotal' => (int) (clone $categories)->sum('quota'),
            'netRevenue' => (int) EventOrder::where('tenant_id', $tenant->id)
                ->whereIn('status', ['paid', 'completed'])->sum('net_amount'),
            'balance' => $tenant->balance,
            'upcoming' => $tenant->events()->with('venue')
                ->withSum('ticketCategories as quota_total', 'quota')
                ->withSum('ticketCategories as sold_total', 'sold_qty')
                ->whereIn('status', ['published', 'ongoing'])
                ->orderBy('starts_at')->limit(5)->get(),
            'switches' => TicketCategory::with('event')
                ->where('tenant_id', $tenant->id)->where('is_active', true)
                ->whereHas('event', fn ($q) => $q->where('status', 'published'))
                ->orderBy('event_id')->orderBy('sort_order')->limit(8)->get(),
        ]);
    }
}
