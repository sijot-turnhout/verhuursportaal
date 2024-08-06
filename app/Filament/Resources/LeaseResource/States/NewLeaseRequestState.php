<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaseResource\States;

/**
 * NewLeaseRequestState Class
 *
 * Represents the initial state of a lease when a new request has been made. This class extends the
 * base LeaseState class and provides the specific implementation for a lease that is in the request phase.
 *
 * In this state, the lease is newly requested and awaiting further action, such as review, approval, or
 * additional information. This is typically the starting point in the lease lifecycle.
 */
final class NewLeaseRequestState extends LeaseState
{
}
