@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h4 class="mb-0">Detail Event StageTix</h4>
    </div>
    <div class="card-body">
        <h3>{{ $event->title }}</h3>
        <p><span class="badge bg-secondary">{{ $event->category }}</span> | Status: <span class="badge bg-success">{{ ucfirst($event->status) }}</span></p>
        <hr>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Venue / Lokasi:</strong>
                <p>{{ $event->venue_name }}</p>
            </div>
            <div class="col-md-6">
                <strong>Waktu Pelaksanaan:</strong>
                <p>{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y H:i') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y H:i') }}</p>
            </div>
        </div>

        @if($event->banner_path)
            <div class="mb-3">
                <strong>Poster Event:</strong><br>
                <img src="{{ asset('storage/' . $event->banner_path) }}" alt="Banner Event" class="img-fluid rounded mt-2" style="max-height: 300px;">
            </div>
        @endif

        <div class="mb-3">
            <strong>Deskripsi:</strong>
            <p>{{ $event->description }}</p>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('events.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">Edit Event</a>
        </div>
    </div>
</div>
@endsection