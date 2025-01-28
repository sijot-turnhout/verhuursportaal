<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\Actions\StateTransitions;

use App\Enums\LeaseStatus;
use App\Filament\Resources\LeaseResource\ValueObjects\CancellationDataObject;
use App\Filament\Support\Actions\StateTransitionAction;
use App\Models\Lease;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * Class TransitionToCancelledAction
 *
 * Represents an action that transitions a lease's status to "Cancelled".
 * This action is designed for use in the Filament resource for leases and includes UI logic, authorization checks, and state transition handling.
 *
 * @package App\Filament\Resources\LeaseResource\Actions\StateTransitions
 */
final class TransitionToCancelledAction extends StateTransitionAction
{
    /**
     * Create a new instance of the action with pre-configured settings.
     *
     * @param  string|null $name  The optional name of the action. Defaults to 'transition-to-cancelled'.
     * @return static             A fully configured instance of the TransitionToCancelledAction.
     */
    public static function make(?string $name = null): static
    {
        return self::buildStateTransitionAction(name: 'transition-to-cacnelled', label: 'Markeren als geannuleerd', finalState: LeaseStatus::Cancelled)
            ->visible(fn(Lease $lease): bool => self::canTransition($lease))
            ->requiresConfirmation()
            ->modalHeading(trans('aanvraag annuleren'))
            ->modalWidth(MaxWidth::Large)
            ->modalDescription(trans('De huurder heeft aangegeven dat hij/zij de verhuring wenst te annuleren. Of de verhuur kan doormiddel van omstandigheden niet doorgaan. Enkel vragen we u nog de redenen voor de annulatie op te geven hieronder.'))
            ->form(fn(): array => self::cancellationModalForm())
            ->action(fn(array $data, Lease $lease) => self::performFormActionLogic($data, $lease));
    }

    /**
     * Determine if the current user is authorized to perform the action on the given lease.
     *
     * This method ensures:
     * - The user has permission to update the lease.
     * - The lease's current status is within the allowed states for transitioning to "Cancelled".
     *
     * @param  Lease $model The resource entity to perform the authorization check on.
     * @return bool         True if the user is authorized to perform the action; otherwise, false
     */
    public static function canTransition(Model $model): bool
    {
        return Gate::allows('update', $model) && $model->status->in(enums: self::configureAllowedStates());
    }

    /**
     * {@inheritDoc}
     */
    public static function configureAllowedStates(): array
    {
        return [LeaseStatus::Request, LeaseStatus::Quotation, LeaseStatus::Option, LeaseStatus::Confirmed];
    }

    /**
     * Perform the core logic of the action, which includes transitioning the lease to the "Cancelled" state
     * and handling addtional form data such as the cancellation reason.
     *
     * @param  array<mixed> $data  The cancellation data that is submitted by the authenticated user.
     * @param  Lease        $model The resource entity to perform the state transition on.
     * @return void
     */
    public static function performFormActionLogic(array $data, Model $model): void
    {
        $cancellationDataObject = new CancellationDataObject(cancellationReason: $data['cancellation_reason']);
        $model->state()->transitionToCancelled($cancellationDataObject);
    }

    /**
     * Define the form structure for the cancellation modal.
     * This form includes a required textarea for the user to provide the reason for cancellation.
     *
     * @return array<int, TextArea>  The array of form components to be displayed in the model.
     */
    private static function cancellationModalForm(): array
    {
        return [
            Textarea::make('cancellation_reason')
                ->label('Reden tot annulatie')
                ->translateLabel()
                ->placeholder('Beschrijf kort waarom de verhuring/aanvraag is geannuleerd')
                ->rows(4)
                ->required(),
        ];
    }
}
