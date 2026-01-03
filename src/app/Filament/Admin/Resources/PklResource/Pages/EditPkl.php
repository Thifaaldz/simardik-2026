<?php

namespace App\Filament\Admin\Resources\PklResource\Pages;

use App\Filament\Admin\Resources\PklResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPkl extends EditRecord
{
    protected static string $resource = PklResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
