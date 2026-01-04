<?php

namespace App\Filament\Admin\Resources\DocumentResource\Pages;

use App\Filament\Admin\Resources\DocumentResource;
use App\Models\SubKategoriDokumen;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default values for required fields
        $data['status_dokumen'] = $data['status_dokumen'] ?? 'aktif';
        $data['tingkat_kerahasiaan'] = $data['tingkat_kerahasiaan'] ?? 'publik';
        $data['created_by'] = $data['created_by'] ?? Auth::id();

        // Auto-generate kode_dokumen and nama_dokumen if sub_kategori_dokumen_id is set
        if (isset($data['sub_kategori_dokumen_id'])) {
            $subKategori = SubKategoriDokumen::with('kategori')->find($data['sub_kategori_dokumen_id']);

            // Set kategori_dokumen_id from the relationship
            $data['kategori_dokumen_id'] = $subKategori?->kategori?->id;

            // Generate kode_dokumen
            $last = \App\Models\Document::latest('id')->first();
            $number = $last ? intval(substr($last->kode_dokumen, strrpos($last->kode_dokumen, '-') + 1)) + 1 : 1;
            $prefix = $subKategori?->kode_prefix ?? 'DOC';
            $kode = $prefix . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
            $data['kode_dokumen'] = $kode;

            // Generate nama_dokumen
            $data['nama_dokumen'] = ($subKategori?->nama_sub_kategori ?? 'Dokumen') . ' - ' . $kode;
        }

        return $data;
    }
}
