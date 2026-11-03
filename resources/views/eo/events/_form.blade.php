@php
$wib = fn ($field) => old($field, isset($event) ? $event->{$field}?->copy()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i') : null);
$locked = isset($event) && in_array($event->status, ['ongoing', 'finished'], true);
@endphp
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @isset($event) @method('PUT') @endisset

    <section class="space-y-4 rounded-xl border border-zinc-200 bg-white p-4 sm:p-6 dark:border-zinc-800 dark:bg-zinc-900">
        <h2 class="font-semibold">Informasi konser</h2>
        <x-input name="title" label="Nama event" required maxlength="150" :value="old('title', $event->title ?? '')" placeholder="mis. Senandung Senja Festival" />
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <x-input name="genre" label="Genre" list="genre-list" required maxlength="40" :value="old('genre', $event->genre ?? '')" />
                <datalist id="genre-list">
                    @foreach (['Pop', 'Rock', 'Jazz', 'Indie', 'EDM', 'Hip-Hop', 'Dangdut', 'Metal', 'Folk', 'Reggae', 'Klasik', 'K-Pop'] as $g)<option value="{{ $g }}">@endforeach
                </datalist>
            </div>
            <x-select name="venue_id" label="Venue" required>
                <option value="">Pilih venue</option>
                @foreach ($venues as $v)
                    <option value="{{ $v->id }}" @selected((string) old('venue_id', $event->venue_id ?? '') === (string) $v->id)>{{ $v->name }} — {{ $v->city }} (kap. {{ number_format($v->capacity, 0, ',', '.') }})</option>
                @endforeach
            </x-select>
        </div>
        <x-textarea name="description" label="Deskripsi & line-up">{{ old('description', $event->description ?? '') }}</x-textarea>
        <div>
            <x-input name="poster" type="file" label="Poster (JPG/PNG, maks 2 MB)" accept="image/*" />
            @if (isset($event) && $event->poster_path)
                <img src="{{ asset('storage/'.$event->poster_path) }}" alt="Poster saat ini" class="mt-2 h-24 rounded-lg">
            @endif
        </div>
    </section>

    <section class="space-y-4 rounded-xl border border-zinc-200 bg-white p-4 sm:p-6 dark:border-zinc-800 dark:bg-zinc-900">
        <h2 class="font-semibold">Jadwal <span class="text-xs font-normal text-zinc-500">(waktu WIB)</span></h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <x-input name="starts_at" type="datetime-local" label="Konser mulai" required :value="$wib('starts_at')" />
            <x-input name="ends_at" type="datetime-local" label="Konser selesai" :value="$wib('ends_at')" />
            <x-input name="sales_start_at" type="datetime-local" label="Penjualan dibuka" :value="$wib('sales_start_at')" />
            <x-input name="sales_end_at" type="datetime-local" label="Penjualan ditutup" :value="$wib('sales_end_at')" hint="Tidak boleh setelah konser dimulai." />
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <x-input name="max_tickets_per_account" type="number" min="1" max="10" label="Maks. tiket per akun" required :value="old('max_tickets_per_account', $event->max_tickets_per_account ?? 4)" hint="Membatasi pembelian agar tidak dikuasai calo." />
            @isset($event)
                <x-select name="status" label="Status" :disabled="$locked">
                    @if ($locked)
                        <option>{{ ['ongoing' => 'Berlangsung', 'finished' => 'Selesai'][$event->status] }}</option>
                    @else
                        @foreach (['draft' => 'Draft (belum tampil)', 'published' => 'Dipublikasikan', 'cancelled' => 'Dibatalkan / dinonaktifkan'] as $k => $l)
                            <option value="{{ $k }}" @selected(old('status', $event->status) === $k)>{{ $l }}</option>
                        @endforeach
                    @endif
                </x-select>
            @endisset
        </div>
    </section>

    <div class="flex flex-wrap gap-3">
        <x-button type="submit">{{ $submit }}</x-button>
        <a href="{{ $cancel }}"><x-button variant="secondary">Batal</x-button></a>
    </div>
</form>
