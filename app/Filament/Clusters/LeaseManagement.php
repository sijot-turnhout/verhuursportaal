<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

/**
 * Class LeaseManagement
 *
 * Represents the Lease managaement cluster in the laravel filament admin panel.
 *
 * A cluster is a group of related resources that can be organized together
 * in the application's navigation. The LeaseManagement cluster is specifically
 * u!sed toà group resources related to leasing of the domain and there respective tenants.
 *
 * @package App\Filament\Clusters
 */
final class LeaseManagement extends Cluster
{
    /**
     * The icon that will be displayed in the application navigation for this cluster.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    /**
     * The label for this cluster in the application navigation.
     *
     * @var string|null
     */
    protected static ?string $navigationLabel = 'Verhuringen';

    /**
     * The breadcrumb label for this cluster.
     *
     * This label is used in breadcrumb navigation to help users undestand
     * their current location within the Lease Management cluster.
     *
     * @var string|null
     */
    protected static ?string $clusterBreadcrumb = 'Verhuringen';
}
