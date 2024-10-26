<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Lease;
use App\Models\Quotation;
use App\Models\Tenant;

final readonly class QuotationGenerator
{
    public static function process(Lease $lease, Tenant $tenant): Quotation
    {
        $quotation = Quotation::create(['lease_id' => $lease->getKey(), 'reciever_id' => $tenant->getKey(), 'description' => self::getFinancialDocumentDescription($lease)]);
        $quotation->creator()->associate(auth()->user())->save();

        $lease->quotation()->associate($quotation)->save();

        return $quotation;
    }

    private static function getFinancialDocumentDescription(Lease $lease): string
    {
        return trans('Deze offerte is voor de verhuringsperiode van :start tot en met :eind. Enkel bij een ondertekende teruggave van de offerte is deze gebonden aan een reservatie', [
            'start' => $lease->arrival_date->format('d/m/Y'),
            'eind' => $lease->departure_date->format('d/m/Y'),
        ]);
    }
}
