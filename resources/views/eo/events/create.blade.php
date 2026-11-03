<x-layouts.eo title="Event baru">
    @include('eo.events._form', ['action' => route('eo.events.store'), 'submit' => 'Simpan sebagai draft', 'cancel' => route('eo.events.index')])
</x-layouts.eo>
