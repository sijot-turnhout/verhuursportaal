<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Lease;
use App\Models\Quotation;
use App\Models\Tenant;

/**
 * Class QuotationGenerator
 *
 * This job class is responsible for generating a new quotation associated with a specific lease
 * and tenant. It creates the quotation record, associates it with the lease, and saves the
 * creator as the authenticated user.
 *
 * @package App\Jobs
 */
final readonly class QuotationGenerator
{
    /**
     * Generate a new quotation for a specified lease and tenant.
     *
     * This method initializes a new `Quotation` instance, linking it to the given `Lease` and
     * `Tenant`, and assigns the currently authenticated user as the quotation's creator.
     * The generated quotation is then saved and associated with the lease.
     *
     * @param  Lease  $lease   The lease associated with the quotation.
     * @param  Tenant $tenant  The tenant receiving the quotation.
     * @return Quotation       The newly created quotation instance.
     */
    public static function process(Lease $lease, Tenant $tenant): Quotation
    {
        $quotation = Quotation::create(['lease_id' => $lease->getKey(), 'reciever_id' => $tenant->getKey(), 'description' => self::getFinancialDocumentDescription($lease)]);
        $quotation->creator()->associate(auth()->user())->save();

        $lease->quotation()->associate($quotation)->save();

        return $quotation;
    }

     /**
     * Generate a description for the quotation document based on the lease dates.
     *
     * This helper method returns a description that includes the rental period, formatted for display
     * and confirming that only a signed quotation secures the reservation.
     *
     * @param  Lease $lease  The lease to extract date information from.
     * @return string        The formatted description string for the quotation.
     */
    private static function getFinancialDocumentDescription(Lease $lease): string
    {
        return trans('Deze offerte is voor de verhuringsperiode van :start tot en met :eind. Enkel bij een ondertekende teruggave van de offerte is deze gebonden aan een reservatie', [
            'start' => $lease->arrival_date->format('d/m/Y'),
            'eind' => $lease->departure_date->format('d/m/Y'),
        ]);
    }
}
