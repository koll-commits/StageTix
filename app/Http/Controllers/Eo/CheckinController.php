<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckinController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = $request->attributes->get('tenant');

        $events = $tenant->events()->whereIn('status', ['published', 'ongoing'])->orderBy('starts_at')->get();

        $counts = Ticket::where('eo_id', $tenant->id)
            ->whereIn('status', ['issued', 'checked_in'])
            ->selectRaw("event_id, COUNT(*) as issued, SUM(status = 'checked_in') as checked_in")
            ->groupBy('event_id')->get()->keyBy('event_id');

        $recent = Ticket::with('event')->where('eo_id', $tenant->id)
            ->where('status', 'checked_in')->orderByDesc('checked_in_at')->limit(10)->get();

        return view('eo.checkin', ['events' => $events, 'counts' => $counts, 'recent' => $recent]);
    }
}
