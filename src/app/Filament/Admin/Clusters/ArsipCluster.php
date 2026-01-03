<?php

namespace App\Filament\Admin\Clusters;

use Filament\Clusters\Cluster;

class ArsipCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Arsip';
    protected static ?string $navigationGroup = 'Manajemen Arsip';
    protected static ?int $navigationSort = 2;
}
