<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\SecurityDepositBuilder;
use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Deposit
 *
 * The Deposit model represents a financial deposit made by a tenant in relation to a lease.
 * This deposit can be in various states such as 'Paid', 'Pending', or 'Refunded' and holds details
 * like the deposit amount and timestamps for when the deposit was paid or refunded.
 *
 * The model is associated with a single lease and tracks the deposit's lifecycle, including its
 * payment and refund status. It also provides a mechanism for managing deposit-related data in
 * the database.
 *
 * @property int|string                       $id               The unique identifier from the security deposit registration in the application.
 * @property int|string                       $lease_id         The unique identifier from the lease that is attached to the security deposit.
 * @property DepositStatus                    $status           The status from the security from the lease.
 * @property float                            $paid_amount      The paid security deposit that the tenant has been made.
 * @property float                            $revoked_amount   The amount that is revoked from the refund at the end of the lease.
 * @property float                            $refunded_amount  The amount that is refunded back to the tenant from the lease.
 * @property string|null                      $note             The nota that admin has to register when the deposit gonne be partially refund or funny revoked.
 * @property \Illuminate\Support\Carbon|null  $paid_at          The timestamp indicating the the securioty has been made to the organisation that handles the lease.
 * @property \Illuminate\Support\Carbon|null  $refund_at        The timestamp that indicates when the security deposit should be refunded.
 * @property \Illuminate\Support\Carbon|null  $refunded_at      The timestamp indicating when the security deposit is refunded to the tenant.
 * @property \Illuminate\Support\Carbon|null  $created_at       The timestamp indicating when the security deposit has been registered in the administration portal.
 * @property \Illuminate\Support\Carbon|null  $updated_at       The timestamp indicating when the security deposit last has been updated.
 *
 * @package App\Models
 */
final class Deposit extends Model
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     * Protects 'id' from being mass-assigned.
     *
     * @var array<string>
     */
    protected $guarded = ['id'];

    /**
     * Default values for model attributes.
     * Sets the initial deposit status to 'Paid' when a deposit is created.
     *
     * @var array<string, DepositStatus>
     */
    protected $attributes = ['status' => DepositStatus::Paid];

    /**
     * List of events that should be recorded in the activity log.
     *
     * This static property defines which model events will trigger activity logging. By default, it is an empty array,
     * meaning no events are recorded unless explicitly specified. You can customize this array to include specific
     * events such as 'created', 'updated', or 'deleted' based on your logging requirements.
     *
     * @var array<string> $recordevents  List of event names that should be logged. For example, ['created', 'updated'].
     */
    protected static $recordEvents = [];

    /**
     * Defines the relationship between the Deposit and Lease models.
     * A deposit is always associated with a specific lease.
     *
     * @return BelongsTo<Lease, covariant $this> The relationship indicating the deposit belongs to a lease.
     */
    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    /**
     * Creates and returns a new instance of SecurityDepositBuilder.
     *
     * This method accepts a query parameter and uses it to instantiate a new SecurityDepositBuilder object, which is then returned.
     * The SecurityDepositBuilder is a custom builder specifically tailored for the Deposit model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query The query builder instance.
     * @return SecurityDepositBuilder<self> Returns an instance of SecurityDepositBuilder
     */
    public function newEloquentBuilder($query): SecurityDepositBuilder
    {
        return new SecurityDepositBuilder($query);
    }

    /**
     * Returns the activity log options for the current model.
     *
     * This method configures the default options for activity logging. It allows specifying
     * the log name that will be used when recording activity entries. The log name is localized
     * using the `trans()` helper function to retrieve the appropriate translation for 'waarbog-betalingen' (security deposit payments).
     *
     * @return LogOptions   The configured log options for activity logging.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    /**
     * Casts model attributes to specific data types.
     * Ensures correct data type handling for attributes like status, amount, and timestamps.
     *
     * @return array<string, string> Attribute names mapped to their respective cast types.
     */
    protected function casts(): array
    {
        return [
            'status' => DepositStatus::class,
            'amount' => 'float',
            'paid_at' => 'datetime',
            'refund_at' => 'datetime',
        ];
    }
}
