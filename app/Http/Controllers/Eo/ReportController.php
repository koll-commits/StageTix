<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\EventOrder;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = $request->attributes->get('tenant');

        $paid = EventOrder::where('tenant_id', $tenant->id)->whereIn('status', ['paid', 'completed']);

        $perEvent = OrderItem::query()
            ->join('event_orders', 'event_orders.id', '=', 'order_items.event_order_id')
            ->where('order_items.tenant_id', $tenant->id)
            ->whereIn('event_orders.status', ['paid', 'completed'])
            ->groupBy('order_items.event_id', 'order_items.event_title_snapshot')
            ->selectRaw('order_items.event_id, order_items.event_title_snapshot as title, SUM(order_items.quantity) as tickets, SUM(order_items.line_total) as gross')
            ->orderByDesc('gross')->get();

        return view('eo.reports', [
            'gross' => (int) (clone $paid)->sum('subtotal_amount'),
            'commission' => (int) (clone $paid)->sum('commission_amount'),
            'net' => (int) (clone $paid)->sum('net_amount'),
            'perEvent' => $perEvent,
        ]);
    }
}
