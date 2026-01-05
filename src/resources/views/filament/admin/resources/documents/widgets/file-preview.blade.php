<x-filament::section>
    <x-slot name="heading">
        Pratinjau Dokumen
    </x-slot>

    @if ($record->mime_type === 'application/pdf')
        <iframe
            src="{{ route('documents.preview', $record) }}"
            class="w-full h-[80vh] border rounded"
        ></iframe>

    @else
        <div class="space-y-2">
            <p class="text-sm text-gray-600">
                Pratinjau tidak tersedia untuk tipe file ini.
            </p>

            <x-filament::button
                tag="a"
                href="{{ route('documents.download', $record) }}"
                icon="heroicon-o-arrow-down-tray"
                target="_blank"
            >
                Download File
            </x-filament::button>
        </div>
    @endif
</x-filament::section>
