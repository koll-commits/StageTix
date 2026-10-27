@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Tambah Event Baru - StageTix Multi-Tenant</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Dummy Tenant ID (Sesuaikan dengan ID Tenant EO yang login) -->
            <input type="hidden" name="tenant_id" value="1">

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label font-weight-bold">Judul Konser / Event</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Soundrenaline 2026">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Kategori Event</label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Konser Musik" {{ old('category') == 'Konser Musik' ? 'selected' : '' }}>Konser Musik</option>
                        <option value="Festival" {{ old('category') == 'Festival' ? 'selected' : '' }}>Festival</option>
                        <option value="Seni & Teater" {{ old('category') == 'Seni & Teater' ? 'selected' : '' }}>Seni & Teater</option>
                    </select>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Venue / Lokasi</label>
                    <input type="text" name="venue_name" class="form-control @error('venue_name') is-invalid @enderror" value="{{ old('venue_name') }}" placeholder="Contoh: Gelora Bung Karno, Jakarta">
                    @error('venue_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status Event</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Tampilkan di Portal)</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Poster / Banner Event</label>
                    <input type="file" name="banner" class="form-control @error('banner') is-invalid @enderror">
                    @error('banner') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi & Ketentuan Event</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('events.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Event StageTix</button>
            </div>
        </form>
    </div>
</div>
@endsection