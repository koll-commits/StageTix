<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\TicketArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TicketAreaController extends Controller
{
    public function store(Request $request, int $event): RedirectResponse
    {
        $tenant = $request->attributes->get('tenant');
        $event = $tenant->events()->findOrFail($event);

        $data = $request->validateWithBag('area', [
            'name' => ['required', 'string', 'max:80',
                Rule::unique('ticket_areas', 'name')->where('eo_id', $tenant->id)->where('event_id', $event->id)],
            'capacity' => ['required', 'integer', 'min:1', 'max:'.($event->venue->capacity ?? 1000000)],
        ]);

        $area = new TicketArea($data);
        $area->eo_id = $tenant->id;
        $area->event_id = $event->id;
        $area->save();

        return back()->with('status', 'Area "'.$area->name.'" ditambahkan.');
    }
}
