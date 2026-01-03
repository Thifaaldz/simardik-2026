<?php

namespace App\Filament\Admin\Resources\SubKategoriDokumenResource\Pages;

use App\Filament\Admin\Resources\SubKategoriDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubKategoriDokumens extends ListRecords
{
    protected static string $resource = SubKategoriDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
