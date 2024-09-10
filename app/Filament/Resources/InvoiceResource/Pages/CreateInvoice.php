<?php

declare(strict_types=1);

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Class CreateInvoice
 *
 * Represents the page for creating a new invoice record within the Filament admin panel.
 * This class extends the `CreateRecord` page from Filament, providing the necessary configuration
 * for creating a new invoice through the Filament resource management system.
 *
 * @package App\Filament\Resources\InvoiceResource
 */
final class CreateInvoice extends CreateRecord
{
    /**
     * The Filament resource class associated with this page.
     *
     * @var string
     */
    protected static string $resource = InvoiceResource::class;
}
