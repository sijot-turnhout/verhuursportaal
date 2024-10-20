<?php

declare(strict_types=1);

namespace App\Actions\Financial;

use App\Models\Lease;
use App\Models\Quotation;
use App\Models\Tenant;

final readonly class StoreQuotationTemplate
{
    public static function process(Lease $lease, Tenant $tenant): void
    {
        $quotation = Quotation::create(['lease_id' => $lease->getKey(), 'reciever_id' => $tenant->getKey(), 'description' => self::getFinancialDocumentDescription($lease)]);
        $lease->quotation()->associate($quotation)->save();
    }

    private static function getFinancialDocumentDescription(Lease $lease): string
    {
        return trans('Deze offerte is voor de verhuringsperiode van :start tot en met :eind. Enkel bij een ondertekende teruggave van de offerte is deze gebonden aan een reservatie', [
            'start' => $lease->arrival_date->format('d/m/Y'),
            'eind' => $lease->departure_date->format('d/m/Y'),
        ]);
    }
}
