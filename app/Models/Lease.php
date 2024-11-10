<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\LeaseBuilder;
use App\Concerns\HasFeedbackSupport;
use App\Concerns\HasUtilityMetrics;
use App\Enums\LeaseStatus;
use App\Filament\Resources\LeaseResource\States;
use App\Filament\Resources\LeaseResource\States\LeaseStateContract;
use App\Observers\LeaseObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Lease
 *
 * @property int                             $id                     The unique identifier from the record in the database.
 * @property string                          $group                  The name of the group/organisation that requested the lease in the application.
 * @property \Illuminate\Support\Carbon      $arrival_date           The timestamp that represent the arrival date of the group at our domain.
 * @property \Illuminate\Support\Carbon      $departure_date         THe timestamp that represent the departure date of the group from our domain.
 * @property int                             $persons                The amount of persons in the group/organisation of the tenant.
 * @property int|null                        $supervisor_id          The unique identifier from the user who follows up on the lease.
 * @property int                             $tenant_id              The unique identifier from the customer. (tenant).
 * @property int|null                        $feedback_id            The unique identifier from the feedback when there is any feedback provided on the lease.
 * @property int|null                        $invoice_id             The unique identifier from the invoice when there is an invoice attached to the lease.
 * @property LeaseStatus                     $status                 The current registered status of the lease in the application.
 * @property \Illuminate\Support\Carbon|null $metrics_registered_at  The timestamp that indicates when the energy utility metrics are registered (finalized)
 * @property \Illuminate\Support\Carbon|null $feedback_valid_until   The timestamp that indicates when the feedback form for the lease will expire
 * @property \Illuminate\Support\Carbon|null $created_at             The timestamp from when the record has been created in the database storage.
 * @property \Illuminate\Support\Carbon|null $updated_at             The timestamp from when the record has been updated last time in the database.
 *
 * @property mixed $tenant
 * @property Invoice $invoice
 *
 * @method bool markAs($leaseStatus)
 */
#[ObservedBy(LeaseObserver::class)]
final class Lease extends Model
{
    /** @use HasFactory<\Database\Factories\LeaseFactory> */
    use HasFactory;
    use HasFeedbackSupport;
    use HasUtilityMetrics;

    /**
     * The database columns that are protected from the mass-assignment system provided by Laravel.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * @var array<string, object|int|string>
     */
    protected $attributes = ['status' => LeaseStatus::Request];

    /**
     * Returns the appropriate LeaseState instance based on the current lease status.
     *
     * This method uses a `match` expression to determine the current state of the lease based on its status.
     * It then returns an instance of the corresponding state class, which handles specific behaviors and transitions
     * for that state. Each lease status maps to a different state class, ensuring the correct state logic is applied
     * at any given point in the lease lifecycle.
     *
     * @return LeaseStateContract   The corresponding state class for the current lease status.
     */
    public function state(): LeaseStateContract
    {
        return match ($this->status) {
            LeaseStatus::Request => new States\LeaseRequestState($this),
            LeaseStatus::Quotation => new States\LeaseQuotationRequestState($this),
            LeaseStatus::Option => new States\LeaseOptionState($this),
            LeaseStatus::Confirmed => new States\LeaseConfirmedState($this),
            LeaseStatus::Finalized => new States\LeaseFinalizedState($this),
            LeaseStatus::Cancelled => new States\LeaseCancelledState($this),
            LeaseStatus::Archived => new States\LeaseArchivedState($this),
        };
    }

    /**
     * The data relation for getting the user information from the user who performs the follow-up of the lease.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne<Deposit, covariant $this>
     */
    public function deposit(): HasOne
    {
        return $this->hasOne(Deposit::class);
    }

    /**
     * The data relation for the locals (lokalen) that are attached to the given lease.
     *
     * @return BelongsToMany<Local, covariant $this>
     */
    public function locals(): BelongsToMany
    {
        return $this->belongsToMany(Local::class);
    }

    /**
     * @return HasMany<Incident, covariant $this>
     */
    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    /**
     * Data relation for getting the information about the tenant that is attached to the lease.
     *
     * @return BelongsTo<Tenant, covariant $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Data relation for getting the notes that are attached to the lease.
     *
     * @return MorphMany<Note, covariant $this>
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Data relation for getting the invoice that is attached to the lease.
     *
     * @return BelongsTo<Invoice, covariant $this>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Retrieve all documents associated with this entity.
     *
     * This method defines a one-to-many relationship, linking each instance of this model
     * (e.g., a Lease or User) to multiple Document records. It allows retrieval and management
     * of all related documents, typically for models that can "own" documents for record-keeping,
     * compliance, or supporting information.
     *
     * Example use cases include fetching all documents tied to a particular lease
     * or user in order to manage attachments, verify compliance, or audit changes.
     *
     * @return HasMany<Document, covariant $this> The related Document instances associated with this entity.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * @return BelongsTo<Quotation, covariant $this>
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Creates and returns a new instance of LeaseBuilder.
     *
     * This method accepts a query parameter and uses it to instantiate a new
     * LeaseBuilder object, which is then returned. The LeaseBuilder is a
     * custom query builder specifically tailored for the Lease model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query  The query builder instance.
     * @return LeaseBuilder<self> Returns an instance of LeaseBuilder.
     */
    public function newEloquentBuilder($query): LeaseBuilder
    {
        return new LeaseBuilder($query);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => LeaseStatus::class,
            'arrival_date' => 'datetime',
            'departure_date' => 'datetime',
        ];
    }

    /**
     * The attribute that computes the lease period field based on the arrival and departure date.
     *
     * @return Attribute<string, never>
     */
    protected function period(): Attribute
    {
        return Attribute::get(fn(): string => "{$this->arrival_date->format('d/m/Y H:i')} - {$this->departure_date->format('d/m/Y H:i')}");
    }
}
