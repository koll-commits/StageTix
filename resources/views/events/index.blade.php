@extends('layouts.app')

@section('content')
<!-- Header Halaman -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold m-0">Event-Saya</h3>
        <small class="text-muted">Kelola event & kategori tiket anda</small>
    </div>
    <a href="{{ route('events.create') }}" class="btn btn-primary">+ Buat Event</a>
</div>

<!-- Tombol Filter -->
<div class="mb-3">
    <button class="btn btn-outline-secondary btn-sm">Filter</button>
</div>

<!-- Grid Card Event (2 Kolom) -->
<div class="row row-cols-1 row-cols-md-2 g-4">
    @forelse($events as $event)
        <div class="col">
            <div class="card h-100">
                <!-- Poster Event -->
                @if($event->banner_path)
                    <img src="{{ asset('storage/' . $event->banner_path) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $event->title }}">
                @else
                    <div class="bg-secondary text-white text-center py-5 fs-4">
                        {{ $event->title }}
                    </div>
                @endif

                <div class="card-body">
                    <span class="badge bg-success mb-2">{{ ucfirst($event->status) }}</span>
                    <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                    <p class="card-text text-muted mb-1">{{ \Carbon\Carbon::parse($event->start_date)->format('d F Y') }}</p>
                    <p class="card-text text-muted small">Venue: {{ $event->venue_name }}</p>

                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-info btn-sm text-white flex-fill">Detail</a>
                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-outline-primary btn-sm flex-fill">Edit</a>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Hapus event ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-secondary text-center">Belum ada data event.</div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $events->links() }}
</div>
@endsection