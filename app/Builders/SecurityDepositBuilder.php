<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Deposit;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class SecurityDepositBuilder
 *
 * This class extends Laravel's `Builder` anbd provides custom query-building methods specific to the `Deposit` model.
 * It allows setting the status of a lease and unlocking easier to read methods for handling the interaction with the security deposits.
 * It alsop adds flexibility and convenience for interacting with `Deposit` records.
 *
 * @template TModelClass of Deposit
 * @extends Builder<Deposit>
 *
 * @package App\Builders
 */
final class SecurityDepositBuilder extends Builder
{
}
