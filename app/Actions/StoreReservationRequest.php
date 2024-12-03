<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\StoreReservation;
use App\DataObjects\ReservationDataObject;
use App\Filament\Resources\LeaseResource\Pages\ViewLease;
use App\Jobs\PerformRiskAssesment;
use App\Jobs\QuotationGenerator;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Handles reservation requests.
 *
 * This action class orchestrates the process of creating and processing reservation requests.
 * It includes tasks like finding or creating tenants, registering leases,
 * sending notifications, and triggering risk assessments.
 *
 * @package App\Actions
 */
final readonly class StoreReservationRequest implements StoreReservation
{
    /**
     * Processes the reservation request.
     *
     * This method orchestrates the entire reservation process, ensuring data integrity
     * and triggering necessary actions.
     *
     * @param  ReservationDataObject $reservationDataObject The data object that contains the initial request data in a mapped form. for the reservation request.
     * @return void
     */
    public function process(ReservationDataObject $reservationDataObject): void
    {
        DB::transaction(function () use ($reservationDataObject): void {
            $lease = $this->registerLeaseReservartion($reservationDataObject);
            $tenant = $this->registerOrFindTenant($reservationDataObject, $lease);

            if ($reservationDataObject->wantsQuotation()) {
                QuotationGenerator::process($lease, $tenant);
            }

            // Notification sending
            $tenant->sendOutReservationConfirmation($lease);
            $this->sendOutNotificationToTheBackend($lease);
        });

        flash(trans('Wij hebben u reservatie aanvraag goed ontvangen. En gaan hier zo snel mogelijk mee aan de slag.'), 'alert-success');
    }

    /**
     * Finds an existing tenant by email or registrers a new one.
     *
     * This method prioritizes finding an existing tenant based on their email address.
     * If no match is found, a new tenant record is created using the provided information.
     *
     * @param  ReservationDataObject $reservationDataObject The data obejct that contains all the needed information for storing the reservation.
     * @return Tenant                                       The found or created tenant instance.
     */
    public function findTenantByEmailOrRegister(ReservationDataObject $reservationDataObject): Tenant
    {
        return Tenant::query()->where('email', $reservationDataObject->getEmail())
            ->firstOr(fn(): Tenant|Model => Tenant::query()->create($reservationDataObject->getTenantInformation()->toArray()));
    }

    /**
     * Registers a new lease reservation.
     *
     * This method creates a new lease record in the database using the provided lease information.
     *
     * @param ReservationDataObject $reservationDataObject The data object containing the reservation details.
     * @return Lease                                       The newly created lease instance.
     */
    private function registerLeaseReservartion(ReservationDataObject $reservationDataObject): Lease
    {
        return Lease::create($reservationDataObject->getLeaseInformation()->toArray());
    }

    /**
     * Registers or finds a tenant and associates them with the lease.
     *
     * This method first finds or creates a tenant and then associates the newly created lease
     * with the tenant. Additionally, it dispatches a job to perform a risk assessment on the lease.
     *
     * @param  ReservationDataObject $reservationDataObject  The data object containing the reservation details.
     * @param  Lease                 $lease                  The newly created lease instance.
     * @return Tenant                                        The tenant associated with the lease.
     */
    private function registerOrFindTenant(ReservationDataObject $reservationDataObject, Lease $lease): Tenant
    {
        $tenant = $this->findTenantByEmailOrRegister($reservationDataObject);
        $tenant->leases()->save($lease);

        dispatch(new PerformRiskAssesment($lease, $tenant));

        return $tenant;
    }

    /**
     * This method will allow us to send out notifications to the users of the platform.
     * In the backend. So We can keep them informed there about the newly created request.
     *
     * @param  Lease $lease The database entity of the lease that has been created through the request.
     * @return void
     */
    private function sendOutNotificationToTheBackend(Lease $lease): void
    {
        User::all()->each(function ($user) use ($lease): void {
            Notification::make()
                ->icon('heroicon-o-document-text')
                ->iconColor('success')
                ->title('Nieuwe aanvraag tot verhuring')
                ->body('Er is een verhuring nieuwe aangevraagd in de applicatie.')
                ->actions([
                    Action::make('viewLease')
                        ->label('Bekijk verhuring')
                        ->translateLabel()
                        ->url(ViewLease::getUrl(['record' => $lease]))
                        ->markAsRead(),
                ])
                ->sendToDatabase($user);
        });
    }
}
