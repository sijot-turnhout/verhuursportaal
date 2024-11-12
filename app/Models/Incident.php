<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\IncidentCodes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property IncidentCodes $status
 */
final class Incident extends Model
{
    protected $guarded = ['id'];

    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne<Lease, covariant $this>
     */
    public function lease(): HasOne
    {
        return $this->hasOne(Lease::class);
    }

    protected function casts(): array
    {
        return ['incident_code' => IncidentCodes::class];
    }
}
