<?php

namespace App\Filament\Admin\Clusters;

use Filament\Clusters\Cluster;

class ManajemenDokumenCluster extends Cluster
{
    // Use a valid heroicon name
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Manajemen Dokumen';
    protected static ?string $navigationGroup = 'Manajemen Arsip';
    protected static ?int $navigationSort = 1;
}
