<?php

namespace App\Filament\Admin\Resources\DocumentResource\Pages;

use App\Filament\Admin\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\SubKategoriDokumen;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public ?int $selectedKategori = null;
    public ?int $selectedSubKategori = null;

    public function mount(): void
    {
        $this->selectedKategori = request()->query('selectedKategori') ? (int) request()->query('selectedKategori') : null;
        $this->selectedSubKategori = request()->query('selectedSubKategori') ? (int) request()->query('selectedSubKategori') : null;
    }

    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getTableQuery();

        if ($this->selectedKategori) {
            $query = $query->whereHas('subKategori', function ($q) {
                $q->where('kategori_dokumen_id', $this->selectedKategori);
            });
        }

        if ($this->selectedSubKategori) {
            $query = $query->where('sub_kategori_dokumen_id', $this->selectedSubKategori);
        }

        return $query;
    }
}
