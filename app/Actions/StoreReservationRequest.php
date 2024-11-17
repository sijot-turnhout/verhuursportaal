<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\StoreReservation;
use App\DataObjects\ReservationDataObject;
use App\Filament\Resources\LeaseResource\Pages\ViewLease;
use App\Jobs\QuotationGenerator;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final readonly class StoreReservationRequest implements StoreReservation
{
    /**
     * The method that handles the storage process of the reservation in the applicitaon.
     * Mainly we do a couple things here such as find or creating the tenant in the application backend.
     * And creating the lease reservation and sending out the confirmation mail to the tenant.
     *
     * @param  ReservationDataObject $reservationDataObject The data object that contains the initial request data in a mapped form. for the reservation request.
     */
    public function process(ReservationDataObject $reservationDataObject): void
    {
        DB::transaction(function () use ($reservationDataObject): void {
            $lease = Lease::create($reservationDataObject->getLeaseInformation()->toArray());

            $tenant = $this->findTenantByEmailOrRegister($reservationDataObject);
            $tenant->leases()->save($lease);

            if ($reservationDataObject->wantsQuotation()) {
                QuotationGenerator::process($lease, $tenant);
            }

            // Notification sending
            $tenant->sendOutReservationConfirmation();
            $this->sendOutNotificationToTheBackend($lease);
        });

        flash(trans('Wij hebben u reservatie aanvraag goed ontvangen. En gaan hier zo snel mogelijk mee aan de slag.'), 'alert-success');
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

    /**
     * This method will allow us to send out notifications to the users of the platform.
     * In the backend. So We can keep them informed there about the newly created request.
     *
     * @param  Lease $lease The entioty of the lease reservation that has been stored.
     * @return void
     */
    private function sendOutNotificationToTheBackend(Lease $lease): void
    {
        User::all()->each(function ($user) use ($lease): void {
            Notification::make()
                ->icon('heroicon-o-document-text')
                ->iconColor('success')
                ->title('Nieuwe aanvraag tot verhuring')
                ->body('Er is een nieuwe aangevraagd in de applicatie.')
                ->actions([
                    Action::make(trans('Bekijk aanvraag'))
                        ->markAsRead()
                        ->url(fn() => ViewLease::getUrl(['record' => $lease])),
                ])
                ->sendToDatabase($user);
        });
    }
}
