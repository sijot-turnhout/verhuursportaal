<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\StoreReservation;
use App\DataObjects\ReservationDataObject;
use App\Models\Tenant;
use App\Models\User;
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
            $tenant = $this->findTenantByEmailOrRegister($reservationDataObject);
            $tenant->leases()->create($reservationDataObject->getLeaseInformation()->toArray());
            $tenant->sendOutReservationConfirmation();

            $this->sendOutNotificationToTheBackend();
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
     */
    private function sendOutNotificationToTheBackend(): void
    {
        User::all()->each(function ($user): void {
            Notification::make()
                ->icon('heroicon-o-document-text')
                ->iconColor('success')
                ->title('Nieuwe aanvraag tot verhuring')
                ->body('Er is een nieuwe aangevraagd in de applicatie.')
                ->sendToDatabase($user);
        });
    }
}
