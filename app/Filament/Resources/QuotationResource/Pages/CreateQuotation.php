<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationResource\Pages;

use App\Filament\Resources\QuotationResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Class CreateQuotation
 *
 * This class handles the creation of a new Quotation record within the `QuotationResource`.
 * It extends the Filament `CreateRecord` class, which provides the core functionality for
 * rendering the create form and handling the record creation logic.
 *
 * @package App\Filament\Resources\QuotationResource\Pages
 */
final class CreateQuotation extends CreateRecord
{
    /**
     * Specifies the resource class associated with this page.
     * This resource is responsible for managing quotation records.
     *
     * @var string
     */
    protected static string $resource = QuotationResource::class;
}
