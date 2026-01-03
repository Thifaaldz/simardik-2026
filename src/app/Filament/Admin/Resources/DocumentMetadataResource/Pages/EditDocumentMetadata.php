<?php

namespace App\Filament\Admin\Resources\DocumentMetadataResource\Pages;

use App\Filament\Admin\Resources\DocumentMetadataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentMetadata extends EditRecord
{
    protected static string $resource = DocumentMetadataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
