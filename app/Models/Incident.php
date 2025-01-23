<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\IncidentCodes;
use App\Enums\IncidentImpact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * The incidlent model represents an "incident" record in the database.
 *
 * @property int                         $id                The unique identifier from the incident in the application storage.
 * @property int                         $user_id           The unique dentifier from the user who has registered the incident.
 * @property int                         $tenant_id         The unique identifier from the tenant that is associated with the incident.
 * @property IncidentImpact              $impact_score      The severity of the incident, categorized using the 'IncidentImpact' enum.
 * @property string                      $description       A description or needed information about the incident that has been occured.
 * @property IncidentCodes               $incident_code     A specified category or type incident, that has been defined in the 'IncidentCodes' enum.
 * @property \Illuminate\Support\Carbon  $created_at        The timestamp from the moment the record has been created in the application database.
 * @property \Illuminate\Support\Carbon  $updated_a         The timestamp from the moment that the record has been modified in the application database.
 *
 * @method User  user()   The entoity from the user that is attached as creator for the incident.
 * @method Lease lease()  The entity for the lease that is attached to the incident.
 */
final class Incident extends Model
{
    /**
     * Protected attributes that cannot be mass assigned.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Defined the relationship to the User model.
     * An incident belongs to one user who reported the incident or is responsible for it.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Defines the relationship to the Lease model.
     * An incident may have one related lease.
     *
     * @return HasOne<Lease, covariant $this>
     */
    public function lease(): HasOne
    {
        return $this->hasOne(Lease::class);
    }

    /**
     * Cast certain fields to specific data types or enums.
     *
     * - **incident_code**: Automatically cast to the `IncidentCodes` enum.
     * - **impact_score**: Automatically cast to the `IncidentImpact` enum.
     *
     * @return array<string, string> The cast definitions.
     */
    protected function casts(): array
    {
        return [
            'incident_code' => IncidentCodes::class,
            'impact_score' => IncidentImpact::class,
        ];
    }
}
