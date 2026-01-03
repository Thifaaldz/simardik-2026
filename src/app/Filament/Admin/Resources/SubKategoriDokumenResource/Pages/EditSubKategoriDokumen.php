<?php

namespace App\Filament\Admin\Resources\SubKategoriDokumenResource\Pages;

use App\Filament\Admin\Resources\SubKategoriDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubKategoriDokumen extends EditRecord
{
    protected static string $resource = SubKategoriDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
