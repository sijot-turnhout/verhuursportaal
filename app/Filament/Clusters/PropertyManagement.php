<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

/**
 * Class PropertyManagement
 *
 * Represents the Property Management cluster in the Filament admin panel.
 *
 * This cluster is used to group resources related to property management,
 * such as handling issues, managing properties, and other related functionalities.
 * It helps organize these resources in the application’s navigation under a unified
 * section dedicated to property management.
 *
 * @extends App\Filament\Clusters
 */
final class PropertyManagement extends Cluster
{
    /**
     * The icon that will be displayed in the application navigation for this cluster.
     * This icon helps users visually identify the Property Management section.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = "heroicon-o-wrench-screwdriver";

    /**
     * The label for this cluster in the application navigation.
     * This label is used to identify the cluster in the navigation menu.
     *
     * @var string|null
     */
    protected static ?string $navigationLabel = "infrastructuur";

    /**
     * The breadcrumb label for this cluster.
     *
     * This label is used in breadcrumb navigation to help users understand
     * their current location within the Property Management section.
     *
     * @var string|null
     */
    protected static ?string $clusterBreadcrumb = "Infrastructuur";
}
