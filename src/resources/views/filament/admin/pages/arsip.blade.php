@php
use App\Models\KategoriDokumen;
@endphp

<x-filament::page>
    <div class="grid grid-cols-6 gap-4">
        <div class="col-span-2">
            <div class="bg-white shadow rounded p-4 text-sm text-gray-600">
                <h3 class="text-lg font-semibold mb-2">Arsip</h3>
                <p class="mb-2">Silakan pilih <strong>Arsip</strong> atau <strong>Sub-Arsip</strong> dari sub-navigasi di sebelah kiri; tabel dokumen akan ditampilkan di kanan.</p>
                <p class="text-xs text-gray-500">Tip: Klik salah satu sub-arsip (mis. Ijazah) untuk melihat dokumen terkait di tabel.</p>
            </div>
        </div>

        <div class="col-span-4 bg-white shadow rounded p-4">
            <h3 class="text-lg font-semibold mb-3">Tabel Dokumen</h3>

            {{ $this->table }}
        </div>
    </div>
</x-filament::page>
