<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\RegisterInitialUtilityMetrics;
use App\Models\Lease;
use App\Support\Auditable;

/**
 * LeaseObserver class.
 *
 * This observer handles events for the Lease model. It performs actions
 * when a lease is created or updated.
 */
final readonly class LeaseObserver
{
    use Auditable;

    /**
     * Handle the Lease "created" event.
     *
     * When a lease is created, this method dispatches a job to register
     * initial utility metrics.
     *
     * @param  Lease $lease The lease instance that was created.
     * @return void
     */
    public function created(Lease $lease): void
    {
        $this->registerAuditEntry(logName: 'verhuringen', event: 'registratie', performedOn: $lease, auditEntry: trans('De verhuringsaanvraag is geregistreerd in de applicatie.'));

        RegisterInitialUtilityMetrics::dispatch($lease);
    }

    /**
     * Handle the "updated" event.
     *
     * @param  Lease $lease The lease instance that was updated
     * @return void
     */
    public function updated(Lease $lease): void
    {
        $this->registerAuditEntry(logName: 'verhuringen', event: 'wijziging', performedOn: $lease, auditEntry: trans('Heeft de gegevens van de verhuringsaanvraag gewijzigd'));
    }
}
