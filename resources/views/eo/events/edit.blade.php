<x-layouts.eo title="Ubah event">
    @include('eo.events._form', ['action' => route('eo.events.update', $event->id), 'submit' => 'Simpan perubahan', 'cancel' => route('eo.events.show', $event->id)])
</x-layouts.eo>
