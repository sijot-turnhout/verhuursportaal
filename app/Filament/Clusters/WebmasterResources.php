<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;


/**
 * Class WebmasterResources
 *
 * Represents the Webmaster Resources cluster in the Filament admin panel.
 *
 * This cluster is used to group resources related to the settings that can be handled by webmasters
 * such as user management or viewing logs. It helps organize these resources in the application’s
 * navigation under a unified section dedicated to this items.
 *
 * @package App\Filament\Cluster
 */
final class WebmasterResources extends Cluster
{
    /**
     * The icon that will be displayed in the application navigation for this cluster.
     * This icon helps users visually identify the Webmaster resources clusters.
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    /**
     * The label for this cluster in the application navigation.
     * This label is used to identify the cluster in the navigation menu.
     *
     * @var string|null
     */
    protected static ?string $navigationLabel = 'Instellingen';

    /**
     * The breadcrumb label for this cluster.
     *
     * This label is used in breadcrumb navigation to help users understand
     * their current location within the Webmaster resources section.
     *
     * @var string|null
     */
    protected static ?string $clusterBreadcrumb = 'Instellingen';

    /**
     * The sort order that will be applied to the application navigation.
     * This determines the position where this item will appear in the navigation list.
     *
     * @var int|null
     */
    protected static ?int $navigationSort = 10;
}
