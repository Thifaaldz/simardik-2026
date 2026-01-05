<?php

namespace App\Filament\Admin\Resources\DocumentResource\Pages;

use App\Filament\Admin\Resources\DocumentResource;
use App\Filament\Admin\Resources\DocumentResource\Widgets\DocumentFilePreview;
use Filament\Resources\Pages\ViewRecord;

class ViewDocument extends ViewRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getFooterWidgets(): array
    {
        return [
            DocumentFilePreview::class,
        ];
    }
}
