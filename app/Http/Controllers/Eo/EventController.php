<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EventController extends Controller
{
    private const STATUSES = ['draft', 'published', 'ongoing', 'finished', 'cancelled'];

    public function index(Request $request): View
    {
        $tenant = $request->attributes->get('tenant');
        $status = $request->query('status');

        $events = $tenant->events()->with('venue')
            ->withSum('ticketCategories as quota_total', 'quota')
            ->withSum('ticketCategories as sold_total', 'sold_qty')
            ->when(in_array($status, self::STATUSES, true), fn ($q) => $q->where('status', $status))
            ->orderByDesc('starts_at')
            ->paginate(10)->withQueryString();

        return view('eo.events.index', ['events' => $events, 'status' => $status]);
    }

    public function create(): View
    {
        return view('eo.events.create', ['venues' => $this->venues()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant = $request->attributes->get('tenant');
        $data = $this->payload($request->validate($this->rules($request)));
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['status'] = 'draft';

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event = $tenant->events()->create($data);

        return redirect()->route('eo.events.show', $event->id)
            ->with('status', 'Event dibuat sebagai draft. Tambahkan area dan kategori tiket, lalu publikasikan.');
    }

    public function show(Request $request, int $event): View
    {
        $event = $request->attributes->get('tenant')->events()->with('venue')->findOrFail($event);

        return view('eo.events.show', [
            'event' => $event,
            'areas' => $event->areas()->withSum('ticketCategories as allocated', 'quota')->orderBy('id')->get(),
            'categories' => $event->ticketCategories()->with('area')->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function edit(Request $request, int $event): View
    {
        $event = $request->attributes->get('tenant')->events()->findOrFail($event);

        return view('eo.events.edit', ['event' => $event, 'venues' => $this->venues()]);
    }

    public function update(Request $request, int $event): RedirectResponse
    {
        $event = $request->attributes->get('tenant')->events()->findOrFail($event);
        $locked = in_array($event->status, ['ongoing', 'finished'], true);

        $rules = $this->rules($request);
        $rules['status'] = $locked ? ['nullable'] : ['required', Rule::in(['draft', 'published', 'cancelled'])];
        $data = $this->payload($request->validate($rules));

        if ($locked) {
            unset($data['status']);
        }

        if (($data['status'] ?? null) === 'published'
            && ! $event->ticketCategories()->where('is_active', true)->exists()) {
            return back()->withInput()
                ->withErrors(['status' => 'Tambahkan minimal satu kategori tiket aktif sebelum dipublikasikan.']);
        }

        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);

        return redirect()->route('eo.events.show', $event->id)->with('status', 'Event diperbarui.');
    }

    /** @return array<string, mixed> */
    private function rules(Request $request): array
    {
        $salesEnd = ['nullable', 'date', 'before_or_equal:starts_at'];
        if ($request->filled('sales_start_at')) {
            $salesEnd[] = 'after:sales_start_at';
        }

        return [
            'title' => ['required', 'string', 'max:150'],
            'venue_id' => ['required', Rule::exists('venues', 'id')->where('status', 'active')],
            'genre' => ['required', 'string', 'max:40'],
            'description' => ['nullable', 'string', 'max:5000'],
            'poster' => ['nullable', 'image', 'max:2048'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'sales_start_at' => ['nullable', 'date'],
            'sales_end_at' => $salesEnd,
            'max_tickets_per_account' => ['required', 'integer', 'between:1,10'],
        ];
    }

    /**
     * Input form dalam WIB, disimpan UTC.
     *
     * @param  array<string, mixed>  $valid
     * @return array<string, mixed>
     */
    private function payload(array $valid): array
    {
        foreach (['starts_at', 'ends_at', 'sales_start_at', 'sales_end_at'] as $field) {
            $valid[$field] = filled($valid[$field] ?? null)
                ? Carbon::parse($valid[$field], 'Asia/Jakarta')->utc()
                : null;
        }
        unset($valid['poster']);

        return $valid;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'event';
        $slug = $base;

        for ($i = 2; Event::withTrashed()->where('slug', $slug)->exists(); $i++) {
            $slug = $base.'-'.$i;
        }

        return $slug;
    }

    private function venues()
    {
        return Venue::where('status', 'active')->orderBy('city')->orderBy('name')->get();
    }
}
