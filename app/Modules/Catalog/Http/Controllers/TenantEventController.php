<?php

namespace App\Modules\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Tenant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Endpoint modul Catalog untuk membuktikan isolasi (global scope + scoped binding + policy).
 * Mendukung respon JSON untuk testing/API dan View jika diakses via web browser.
 */
final class TenantEventController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, Tenant $eo): JsonResponse|View
    {
        // Global scope memfilter ke tenant aktif (TenantContext dari middleware).
        $events = Event::query()->orderBy('title')->get();

        if (! $request->wantsJson() && ! $request->is('api/*')) {
            return view('eo.events.index', ['events' => $events, 'status' => $request->query('status')]);
        }

        return response()->json(['tenant' => $eo->slug, 'events' => $events]);
    }

    public function show(Request $request, Tenant $eo, Event $event): JsonResponse|View
    {
        $this->authorize('view', $event);

        if (! $request->wantsJson() && ! $request->is('api/*')) {
            return view('eo.events.show', ['event' => $event]);
        }

        return response()->json($event->only(['id', 'title', 'status']));
    }

    public function update(Request $request, Tenant $eo, Event $event): JsonResponse
    {
        $this->authorize('update', $event);

        $data = $request->validate(['status' => ['required', 'string']]);
        $event->update(['status' => $data['status']]);

        return response()->json(['ok' => true, 'status' => $event->status]);
    }
}
