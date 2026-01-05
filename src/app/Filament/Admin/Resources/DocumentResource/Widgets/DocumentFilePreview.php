<?php

namespace App\Filament\Admin\Resources\DocumentResource\Widgets;

use Filament\Widgets\Widget;

class DocumentFilePreview extends Widget
{
    protected static string $view = 'filament.admin.resources.documents.widgets.file-preview';

    public $record;
}
