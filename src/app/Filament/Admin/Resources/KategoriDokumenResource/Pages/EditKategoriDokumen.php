<?php

namespace App\Filament\Admin\Resources\KategoriDokumenResource\Pages;

use App\Filament\Admin\Resources\KategoriDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriDokumen extends EditRecord
{
    protected static string $resource = KategoriDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
