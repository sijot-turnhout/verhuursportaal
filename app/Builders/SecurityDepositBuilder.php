<?php

declare(strict_types=1);

namespace App\Builders;

use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use App\Models\Deposit;
use App\Models\Lease;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Contracts\Activity;

/**
 * Class SecurityDepositBuilder
 *
 * This class extends Laravel's `Builder` anbd provides custom query-building methods specific to the `Deposit` model.
 * It allows setting the status of a lease and unlocking easier to read methods for handling the interaction with the security deposits.
 * It also adds flexibility and convenience for interacting with `Deposit` records.
 *
 * @template TModelClass of Deposit
 * @extends Builder<Deposit>
 *
 * @todo We need to fix the array<mixed> declarations in the class to improve static analysis (PHPSTAN)
 *
 * @package App\Builders
 */
final class SecurityDepositBuilder extends Builder
{
    /**
     * Registers a new deposit for a lease and logs the action.
     *
     * This method creates a deposit record, associates it with the lease,
     * and logs an audit entry with detailsm about the transaction.
     *
     * @param  Lease          $lease        The lease associated with the deposit.
     * @param  array<mixed>   $depositData  The data of tthe deposit, such as amopunt and other details.
     * @return Deposit|bool                 The newly created deposit instance.
     */
    public function initiateDeposit(Lease $lease, array $depositData): Deposit|bool
    {
        return DB::transaction(function () use ($lease, $depositData): Deposit|bool {
            // Create a new deposit with the provided data and set the payment time to the current timestamp.
            $deposit = Deposit::create(array_merge($depositData, ['paid_at' => now()]));

            // Record this actions in the audit log for traceability.
            $this->recordAuditActionInAuditLog(
                performedOn: $deposit,
                event: trans('waarborg registratie'),
                auditMessage: trans('Heeft de betaling van een waarborg geregistreerd'),
                extraProperties: [
                    'deposit_balance' => $deposit->paid_amount,
                ],
            );

            // Link the deposit to the lease and return the saved deposit.
            return $lease->deposit()->save($deposit);
        });
    }

    /**
     * Marks the deposit as fully refunded and logs the action.
     *
     * Updates the deposit's status to 'FullyRefunded', sets the refunded amount,
     * and logs an audit entry to record the action.
     *
     * @return bool True if the operation succeeded, false otherwise.
     */
    public function initiateRefund(): bool
    {
        return DB::transaction(function (): bool {
            $this->recordAuditActionInAuditLog(
                event: trans('volledige terugbetaling'),
                auditMessage: trans('Heeft de waarborg geregistreerd als volledig terugbetaald.'),
                extraProperties: [
                    'deposit_balance' => $this->model->paid_amount,
                    'refunded_balance' => $this->model->paid_amount,
                ],
            );

            return $this->model->update(attributes: [
                'status' => DepositStatus::FullyRefunded,
                'refunded_at' => now(),
                'refunded_amount' => $this->model->paid_amount,
            ]);
        });
    }

    /**
     * Marks the deposit as withdrawn and logs the action.
     *
     * Withdrawals mean the deposit is retained by the organization without any refund.
     * This methods updates the status to 'Withdrawn', records the withheld amount, als logs the transaction.
     *
     * @param  array<mixed> $depositData   An associative array with additional details about the withdrawal.
     * @return bool                 True if the operation succeeded, false otherwise.
     */
    public function initiateWithdrawal(array $depositData): bool
    {
        return DB::transaction(function () use ($depositData): bool {
            $this->recordAuditActionInAuditLog(
                event: trans('Intrekking van de waarborg'),
                auditMessage: trans('Heeft de waarborg registreerd als volledig ingetrokken'),
                extraProperties: [
                    'deposit_balance' => $this->model->paid_amount,
                    'refunded_balance' => '0.00',
                    'withheld_balance' => $this->model->paid_amount,
                ],
            );

            return $this->model->update(attributes: array_merge($depositData, [
                'status' => DepositStatus::WithDrawn,
                'revoked_amount' => $this->model->paid_amount,
                'refunded_amount' => '0.00',
                'refunded_at' => now(),
            ]));
        });
    }

    /**
     * Processes a partial refund for the deposit and logs the action.
     *
     * Calculates the refundable ampint based on the withheld amount,
     * updoates the deposit's status to 'PartiallyRefunded', and records the transaction.
     *
     * @param  array<mixed> $depositData   An associative array containing details about the withheld amount ('revoked_amount').
     * @return bool                 True if the operation succeeded, false otherwise.
     */
    public function initiatePartiallyRefund(array $depositData): bool
    {
        $calculatedRefund = $this->model->paid_amount - (float) $depositData['revoked_amount'];

        return DB::transaction(function () use ($calculatedRefund, $depositData): bool {
            $this->recordAuditActionInAuditLog(
                event: trans('gedeeltelijke terugbetaling'),
                auditMessage: trans('Heeft een gedeeltelijke terugbetaling van de waarborg geregistreerd'),
                extraProperties: [
                    'refunded_amount' => $calculatedRefund,
                    'revoked_amount' => $depositData['revoked_amount'],
                ],
            );

            return $this->model->update(
                attributes: array_merge($depositData, [
                    'status' => DepositStatus::PartiallyRefunded,
                    'refuned_at' => now(),
                    'refunded_amount' => $calculatedRefund]),
            );
        });
    }

    /**
     * Records an action in the audit log for traceability
     *
     * Uses the activitylog to save details about the performed action,
     * including the event type, message, related model, and extra properties.
     *
     * @param  string        $event            The type of event being logged (e.g., 'Refund').
     * @param  string        $auditMessage     A description of the action performed.
     * @param  array<mixed>  $extraProperties  Additional contextual data to include in the log.
     * @param  Model|null    $performedOn      The model instance the action was performed on (optional).
     * @return Activity|null                   The recorded audit log entry of null if it failed
     */
    private function recordAuditActionInAuditLog(
        string $event,
        string $auditMessage,
        $extraProperties = [],
        ?Model $performedOn = null,
    ): Activity|null {
        return activity(trans('waarborg-betalingen'))
            ->performedOn($performedOn ?? $this->model)
            ->event($event)
            ->causedBy(auth()->user())
            ->withProperties($extraProperties)
            ->log(trans($auditMessage));
    }
}
