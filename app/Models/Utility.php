<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\UtilityMetric;
use App\Enums\UtilityMetricTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property UtilityMetricTypes $name
 */
final class Utility extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Data relation for getting the information about lease that is attached to the utility metric.
     *
     * @return BelongsTo<Lease, self>
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
