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
 * @package App\Builders
 */
final class SecurityDepositBuilder extends Builder
{
    public function initiateDeposit(Lease $lease, array $depositData): Deposit
    {
        return DB::transaction(function () use ($lease, $depositData): Deposit {
            $deposit = Deposit::create(array_merge($depositData, ['paid_at' => now()]));

            $this->recordAuditActionInAuditLog(
                performedOn: $deposit,
                event: trans('waarborg registratie'),
                auditMessage: trans('Heeft de betaling van een waarborg geregistreerd'),
                extraProperties: ['deposit_balance' => $deposit->paid_amount],
            );

            return $lease->deposit()->save($deposit);
        });
    }

    public function initiateRefund(): bool
    {
        return DB::transaction(function (): bool {
            $this->recordAuditActionInAuditLog(
                event: trans('volledige terugbetaling'),
                auditMessage: trans('Heeft de waarborg geregistreerd als volledig terugbetaald.'),
                extraProperties: ['deposit_balance' => $this->model->paid_amount, 'refunded_balance' => $this->model->paid_amount],
            );

            return $this->model->update(attributes: ['status' => DepositStatus::FullyRefunded, 'refunded_at' => now(), 'refunded_amount' => $this->model->paid_amount]);
        });
    }

    public function initiateWithdrawal(array $depositData): bool
    {
        return DB::transaction(function () use ($depositData): bool {
            $this->recordAuditActionInAuditLog(
                event: trans('Intrekking van de waarborg'),
                auditMessage: trans('Heeft de waarborg registreerd als volledig ingetrokken'),
                extraProperties: ['deposit_balance' => $this->model->paid_amount, 'refunded_balance' => '0.00', 'withheld_balance' => $this->model->paid_amount],
            );

            return $this->model->update(
                attributes: array_merge($depositData, ['status' => DepositStatus::WithDrawn, 'revoked_amount' => $this->model->paid_amount, 'refunded_amount' => '0.00', 'refunded_at' => now()]),
            );
        });
    }

    public function initiatePartiallyRefund(array $depositData): bool
    {
        $calculatedRefund = $this->model->paid_amount - (float) $depositData['revoked_amount'];

        return DB::transaction(function () use ($calculatedRefund, $depositData): bool {
            $this->recordAuditActionInAuditLog(
                event: trans('gedeeltelijke terugbetaling'),
                auditMessage: trans('Heeft een gedeeltelijke terugbetaling van de waarborg geregistreerd'),
                extraProperties: ['refunded_amount' => $calculatedRefund, 'revoked_amount' => $depositData['revoked_amount']],
            );

            return $this->model->update(attributes: array_merge($depositData, ['status' => DepositStatus::PartiallyRefunded, 'refuned_at' => now(), 'refunded_amount' => $calculatedRefund]));
        });
    }

    private function recordAuditActionInAuditLog(string $event, string $auditMessage, $extraProperties = [], ?Model $performedOn = null): Activity|null
    {
        return activity(trans('waarborg-betalingen'))
            ->performedOn($performedOn ?? $this->model)
            ->event($event)
            ->causedBy(auth()->user())
            ->withProperties($extraProperties)
            ->log(trans($auditMessage));
    }
}
