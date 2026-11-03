<x-layouts.eo :title="$event->title">
    <x-slot:headerRight>
        <a href="{{ route('eo.events.edit', $event->id) }}"><x-button variant="secondary">Ubah event</x-button></a>
    </x-slot:headerRight>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 sm:p-6 dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold">{{ $event->title }}</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $event->genre }} · {{ $event->venue->name }}, {{ $event->venue->city }}</p>
            </div>
            <x-status-badge :status="$event->status" />
        </div>
        <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-3">
            <div><dt class="text-zinc-500">Konser</dt><dd><x-wib :at="$event->starts_at" /></dd></div>
            <div><dt class="text-zinc-500">Penjualan dibuka</dt><dd><x-wib :at="$event->sales_start_at" /></dd></div>
            <div><dt class="text-zinc-500">Penjualan ditutup</dt><dd><x-wib :at="$event->sales_end_at" /></dd></div>
        </dl>
        @if ($event->description)
            <p class="mt-4 whitespace-pre-line text-sm text-zinc-600 dark:text-zinc-300">{{ $event->description }}</p>
        @endif
    </div>

    {{-- Area / zona --}}
    <section class="mt-6">
        <h2 class="mb-3 text-base font-semibold">Area & zona</h2>
        <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
            <table class="w-full min-w-[28rem] text-left text-sm">
                <thead class="bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    <tr><th class="px-4 py-2">Area</th><th class="px-4 py-2">Kapasitas</th><th class="px-4 py-2">Terpakai kuota</th></tr>
                </thead>
                <tbody>
                    @forelse ($areas as $area)
                        <tr class="border-t border-zinc-200 dark:border-zinc-800">
                            <td class="px-4 py-3">{{ $area->name }}</td>
                            <td class="px-4 py-3">{{ number_format($area->capacity, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ number_format((int) $area->allocated, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-zinc-500">Belum ada area. Tambahkan area terlebih dahulu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <form method="POST" action="{{ route('eo.areas.store', $event->id) }}" class="mt-3 grid gap-3 rounded-xl border border-zinc-200 bg-white p-4 sm:grid-cols-3 dark:border-zinc-800 dark:bg-zinc-900">
            @csrf
            <x-input name="name" bag="area" label="Nama area" required maxlength="80" placeholder="mis. Festival A" :value="old('name')" />
            <x-input name="capacity" bag="area" type="number" min="1" label="Kapasitas" required :value="old('capacity')" />
            <div class="flex items-end"><x-button type="submit" class="w-full">Tambah area</x-button></div>
        </form>
    </section>

    {{-- Kategori tiket --}}
    <section class="mt-6">
        <h2 class="mb-3 text-base font-semibold">Kategori tiket</h2>
        @forelse ($categories as $cat)
            @php $left = max(0, $cat->quota - $cat->sold_qty); @endphp
            <div x-data="{ edit: {{ $errors->category->any() && old('_cat') == $cat->id ? 'true' : 'false' }} }" class="mb-3 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-medium">{{ $cat->name }}
                            @if ($cat->is_sold_out)<x-status-badge status="soldout" class="ms-1" />@endif
                            @unless ($cat->is_active)<x-status-badge status="default" class="ms-1">Nonaktif</x-status-badge>@endunless
                        </p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $cat->area->name }} · <x-rupiah :amount="$cat->price_amount" /> · terjual {{ $cat->sold_qty }} / {{ $cat->quota }} (sisa {{ $left }})</p>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('eo.categories.sold-out', $cat->id) }}">
                            @csrf @method('PATCH')
                            <x-button type="submit" variant="secondary">{{ $cat->is_sold_out ? 'Buka lagi' : 'Tandai sold out' }}</x-button>
                        </form>
                        <x-button type="button" variant="secondary" @click="edit = !edit">Ubah</x-button>
                    </div>
                </div>
                <form x-show="edit" x-cloak method="POST" action="{{ route('eo.categories.update', $cat->id) }}" class="mt-4 grid gap-3 border-t border-zinc-100 pt-4 sm:grid-cols-4 dark:border-zinc-800">
                    @csrf @method('PUT')
                    <input type="hidden" name="_cat" value="{{ $cat->id }}">
                    <x-input name="name" bag="category" label="Nama" required :value="$cat->name" />
                    <x-input name="price_amount" bag="category" type="number" min="0" label="Harga (Rp)" required :value="$cat->price_amount" />
                    <x-input name="quota" bag="category" type="number" :min="max(1, $cat->sold_qty)" label="Kuota" required :value="$cat->quota" :hint="'Minimal '.max(1, $cat->sold_qty).' (sudah terjual).'" />
                    <label class="flex min-h-11 items-center gap-2 self-end text-sm"><input type="checkbox" name="is_active" value="1" @checked($cat->is_active)> Aktif dijual</label>
                    <div class="sm:col-span-4"><x-button type="submit">Simpan kategori</x-button></div>
                </form>
            </div>
        @empty
            <x-empty-state title="Belum ada kategori tiket" description="Tambahkan kategori seperti Festival, Tribun, atau VIP. Event butuh minimal satu kategori aktif untuk dipublikasikan." />
        @endforelse

        @if ($areas->isNotEmpty())
            <form method="POST" action="{{ route('eo.categories.store', $event->id) }}" class="mt-3 grid gap-3 rounded-xl border border-zinc-200 bg-white p-4 sm:grid-cols-5 dark:border-zinc-800 dark:bg-zinc-900">
                @csrf
                <x-input name="name" bag="category" label="Nama kategori" required placeholder="mis. VIP" :value="old('name')" />
                <x-select name="area_id" bag="category" label="Area" required>
                    @foreach ($areas as $a)<option value="{{ $a->id }}" @selected(old('area_id') == $a->id)>{{ $a->name }}</option>@endforeach
                </x-select>
                <x-input name="price_amount" bag="category" type="number" min="0" label="Harga (Rp)" required :value="old('price_amount')" />
                <x-input name="quota" bag="category" type="number" min="1" label="Kuota" required :value="old('quota')" />
                <div class="flex items-end"><x-button type="submit" class="w-full">Tambah kategori</x-button></div>
            </form>
        @endif
    </section>
</x-layouts.eo>
