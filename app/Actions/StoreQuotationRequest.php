<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\StoreQuotation;
use App\DataObjects\ReservationDataObject;
use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Tenant;
use App\Services\FinancialDocumentCreator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final readonly class StoreQuotationRequest implements StoreQuotation
{
    public function process(ReservationDataObject $reservationDataObject): void
    {
        DB::transaction(function () use ($reservationDataObject): void {
            $tenant = $this->findTenantByEmailOrRegister($reservationDataObject);
            $leaseReservation = $this->registerLeaseReservation($reservationDataObject, $tenant);

            /** @phpstan-ignore-next-line */
            $financialDocumentCreator = $this->financialDocumentCreator($leaseReservation);

            /** @phpstan-ignore-next-line  */
            tap($this->newQuotationDocument($leaseReservation), function (Invoice $quotationDocument) use ($financialDocumentCreator): void {
                $financialDocumentAttached = $financialDocumentCreator->attachFinancialDocumentToLease($quotationDocument);

                if ($financialDocumentCreator->automaticInvoiceLineImportEnabled() && $financialDocumentAttached) {
                    $quotationDocument->invoiceLines()->saveMany([
                        $financialDocumentCreator->registerBookingCosts(),
                    ]);
                }
            });

            flash(trans('Wij hebben u vraag omtrent een offerte goed ontvangen. En gaan hier zo snel mogelijk mee aan de slag.'), 'alert-success');
        });
    }

    /**
     * Method for find or storing the tenant in the reservation backend of the application.
     *
     * @param  ReservationDataObject $reservationDataObject The data obejct that contains all the needed information for storing the reservation;
     */
    public function findTenantByEmailOrRegister(ReservationDataObject $reservationDataObject): Tenant
    {
        return Tenant::query()->where('email', $reservationDataObject->getEmail())
            ->firstOr(fn(): Tenant|Model => Tenant::query()->create($reservationDataObject->getTenantInformation()->toArray()));
    }

    private function financialDocumentCreator(Lease $lease): FinancialDocumentCreator
    {
        return new FinancialDocumentCreator($lease, $this->getDocumentDescription($lease));
    }

    private function getDocumentDescription(Lease $lease): string
    {
        return trans('Deze offerte is voor de verhuringsperiode van :start tot en met :eind. Enkel bij een ondertekende teruggave van de offerte is deze gebonden aan een reservatie', [
            'start' => $lease->arrival_date->format('d/m/Y'),
            'eind' => $lease->departure_date->format('d/m/Y'),
        ]);
    }

    private function newQuotationDocument(Lease $leaseReservation): Invoice|Model
    {
        $quotationDueAt = now()->addWeeks(2);

        return $this->financialDocumentCreator($leaseReservation)->newFinancialDocument($quotationDueAt, InvoiceStatus::Quotation_Request);
    }

    private function registerLeaseReservation(ReservationDataObject $reservationDataObject, Tenant $tenant): Lease|Model
    {
        return $tenant->leases()->create($reservationDataObject->getLeaseInformation()->toArray());
    }
}
