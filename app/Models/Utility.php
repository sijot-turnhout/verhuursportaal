<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\UtilityMetric;
use App\Enums\UtilityMetricTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Utility
 *
 * @property int                             $id              The unique identifier from the utility metric in the database.
 * @property int                             $lease_id        The unique identifier from the lease where the utility metrics are attached to.
 * @property UtilityMetricTypes              $name            The name of the utility metric.
 * @property UtilityMetric<self>             $start_value     The value of the metric at the start of their lease;
 * @property UtilityMetric<self>             $end_value       The value of the metric at the end of the tenant their lease.
 * @property int|float                       $unit_price      The price per quantity of the utility metric
 * @property int|float|null                  $usage_total     The total usage metric of the utility that is used by the tenant during their lease.
 * @property int|float|null                  $billing_amount  The total price that will be billend to the customer (tenant).
 * @property \Illuminate\Support\Carbon|null $created_at      The timestamp that indicates when the record has been created in the application.
 * @property \Illuminate\Support\Carbon|null $updated_at      The timestamp that indicates when the record has been updated for the last time.
 *
 * @property-read Lease $lease  The variable for accessing the lease data and theiur belonging methods.
 */
final class Utility extends Model
{
    /** @use HasFactory<\Database\Factories\UtilityFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Data relation for getting the information about lease that is attached to the utility metric.
     *
     * @return BelongsTo<Lease, covariant $this>
     */
    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'name' => UtilityMetricTypes::class,
            'start_value' => UtilityMetric::class,
            'end_value' => UtilityMetric::class,
        ];
    }
}
