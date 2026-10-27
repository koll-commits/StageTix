<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Menampilkan daftar event milik Event Organizer (Tenant) aktif.
     */
    public function index()
{
    // Hapus ::with('ticketCategories')
    $events = Event::latest()->paginate(10);

    return view('events.index', compact('events'));
}

    /**
     * Form tambah event baru.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Menyimpan event baru sesuai spesifikasi SRS StageTix.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id'   => 'required|integer',
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'venue_name'  => 'required|string|max:255',
            'start_date'  => 'required|date|after_or_equal:today',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'status'      => 'required|in:draft,published,completed,cancelled',
            'banner'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string',
        ]);

        $validated['slug'] = Str::slug($request->title) . '-' . time();

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('events/banners', 'public');
            $validated['banner_path'] = $path;
        }

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event StageTix berhasil ditambahkan ke portal!');
    }

    /**
     * Menampilkan detail event beserta daftar kategori tiket.
     */
    // public function show(Event $event)
    // {
    //     $event->load('ticketCategories');
    //     return view('events.show', compact('event'));
    // }
    public function show(Event $event)
{
    // Hapus/komentari baris $event->load('ticketCategories');
    return view('events.show', compact('event'));
}

    /**
     * Form edit event.
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Memperbarui data event StageTix.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'venue_name'  => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'status'      => 'required|in:draft,published,completed,cancelled',
            'banner'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string',
        ]);

        $validated['slug'] = Str::slug($request->title) . '-' . time();

        if ($request->hasFile('banner')) {
            // Hapus banner lama jika ada
            if ($event->banner_path && Storage::disk('public')->exists($event->banner_path)) {
                Storage::disk('public')->delete($event->banner_path);
            }
            $validated['banner_path'] = $request->file('banner')->store('events/banners', 'public');
        }

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Data event StageTix berhasil diperbarui!');
    }

    /**
     * Menghapus event StageTix.
     */
    public function destroy(Event $event)
    {
        if ($event->banner_path && Storage::disk('public')->exists($event->banner_path)) {
            Storage::disk('public')->delete($event->banner_path);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event berhasil dihapus!');
    }
}