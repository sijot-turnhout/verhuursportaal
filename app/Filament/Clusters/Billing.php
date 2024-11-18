<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

/**
 * Class Billing
 *
 * Represents the Billing cluster in the Filament admin panel.
 *
 * A cluster is a group of related resources that can be organized together
 * in the application’s navigation. The Billing cluster is specifically
 * used to group resources related to invoicing and financial transactions.
 *
 * @package App\Filament\Clusters
 */
final class Billing extends Cluster
{
    /**
     * The icon that will be displayed in the application navigation for this cluster.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = "heroicon-o-squares-2x2";

    /**
     * The label for this cluster in the application navigation.
     *
     * @var string|null
     */
    protected static ?string $navigationLabel = "Facturatie";

    /**
     * The breadcrumb label for this cluster.
     *
     * This label is used in breadcrumb navigation to help users understand
     * their current location within the Property Management section.
     *
     * @var string|null
     */
    protected static ?string $clusterBreadcrumb = "Facturatie";
}
