<?php

namespace App\Filament\Admin\Resources\KategoriDokumenResource\Pages;

use App\Filament\Admin\Resources\KategoriDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriDokumens extends ListRecords
{
    protected static string $resource = KategoriDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
