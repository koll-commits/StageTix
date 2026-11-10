<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    private const VISIBLE = ['published', 'ongoing'];

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $genre = (string) $request->query('genre', '');

        $events = Event::with(['venue', 'ticketCategories' => fn ($c) => $c->where('is_active', true)])
            ->whereIn('status', self::VISIBLE)
            ->when($q !== '', fn ($query) => $query->where('title', 'like', '%'.$q.'%'))
            ->when($genre !== '', fn ($query) => $query->where('genre', $genre))
            ->orderBy('starts_at')
            ->paginate(9)->withQueryString();

        $genres = Event::whereIn('status', self::VISIBLE)->distinct()->orderBy('genre')->pluck('genre');

        return view('catalog.index', compact('events', 'genres', 'q', 'genre'));
    }

    public function show(string $slug): View
    {
        $event = Event::with('venue')->whereIn('status', self::VISIBLE)->where('slug', $slug)->firstOrFail();

        $categories = $event->ticketCategories()->with('area')
            ->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

        return view('catalog.show', compact('event', 'categories'));
    }
}
