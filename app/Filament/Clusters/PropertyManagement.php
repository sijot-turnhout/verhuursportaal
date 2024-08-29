<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

final class PropertyManagement extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Lokalenbeheer';

    protected static ?string $clusterBreadcrumb = 'Lokalenbeheer';
}
