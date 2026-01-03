<?php

namespace App\Filament\Admin\Resources\PklResource\Pages;

use App\Filament\Admin\Resources\PklResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPkls extends ListRecords
{
    protected static string $resource = PklResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
