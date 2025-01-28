<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\LeaseBuilder;
use App\Concerns\HasFeedbackSupport;
use App\Concerns\HasUtilityMetrics;
use App\Enums\LeaseStatus;
use App\Enums\RiskLevel;
use App\Filament\Resources\LeaseResource\States;
use App\Filament\Resources\LeaseResource\States\LeaseStateContract;
use App\Filament\Support\Concerns\HasStatusses;
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
 * Class Lease represents a lease agreement within the application.
 *
 * This model includes various attributes and relationships that define the properties and associations of a lease.
 * It also uses specific traits and enums for additional functionalities and type safety.
 *
 * @property int                             $id                      The unique identifier from the record in the database.
 * @property string                          $reference_number        Reference number uniquely identifying the lease.
 * @property int                             $risk_accessment_score   Score indicating the risk level of the lease.
 * @property RiskLevel                       $risk_accessment_label   Label describing the assessed risk level.
 * @property string                          $group                   The name of the group/organisation that requested the lease in the application.
 * @property \Illuminate\Support\Carbon      $arrival_date            The timestamp that represent the arrival date of the group at our domain.
 * @property \Illuminate\Support\Carbon      $departure_date          THe timestamp that represent the departure date of the group from our domain.
 * @property int                             $persons                 The amount of persons in the group/organisation of the tenant.
 * @property int|null                        $supervisor_id           The unique identifier from the user who follows up on the lease.
 * @property int                             $tenant_id               The unique identifier from the customer. (tenant).
 * @property int|null                        $feedback_id             The unique identifier from the feedback when there is any feedback provided on the lease.
 * @property int|null                        $invoice_id              The unique identifier from the invoice when there is an invoice attached to the lease.
 * @property LeaseStatus                     $status                  The current registered status of the lease in the application.
 * @property string                          $cancellation_reason     The reason why the lease requestt is cancelled by the tenant of an admin.
 * @property \Illuminate\Support\Carbon|null $metrics_registered_at   The timestamp that indicates when the energy utility metrics are registered (finalized)
 * @property \Illuminate\Support\Carbon|null $feedback_valid_until    The timestamp that indicates when the feedback form for the lease will expire
 * @property \Illuminate\Support\Carbon|null $cancelled_at            The Timestamp that indicates when the lease request is cancelled.
 * @property \Illuminate\Support\Carbon|null $created_at              The timestamp from when the record has been created in the database storage.
 * @property \Illuminate\Support\Carbon|null $updated_at              The timestamp from when the record has been updated last time in the database.
 *
 * @property mixed $tenant
 * @property Invoice $invoice
 * @property Deposit $deposit
 *
 * @method bool markAs($leaseStatus)
 * @method bool registerCancellation($status)
 */
#[ObservedBy(LeaseObserver::class)]
final class Lease extends Model
{
    /** @use HasFactory<\Database\Factories\LeaseFactory> */
    use HasFactory;
    use HasFeedbackSupport;
    use HasStatusses;
    use HasUtilityMetrics;

    /**
     * List of database columns that are protected from mass assignment.
     * These columns cannot be directly set via mass-assignment
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Default attributes for new Lease instances.
     *
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
     * Defines the relationship to the supervisor of the lease.
     *
     * This belongs-to relationship connects the lease to a User model, representing
     * the person responsible for overseeing the lease's execution and maintenance.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Establishes a one-to-one relationship with the Deposit model.
     *
     * This relationship associates a specific deposit with the lease,
     * typically used for financial securities or down payments.
     *
     * @return HasOne<Deposit, covariant $this>
     */
    public function deposit(): HasOne
    {
        return $this->hasOne(Deposit::class);
    }

    /**
     * Many-to-many relationship with the Local model representing specific venues or locations.
     *
     * Leases can be associated with multiple locations, reflecting the spaces rented
     * for the specific agreement.
     *
     * @return BelongsToMany<Local, covariant $this>
     */
    public function locals(): BelongsToMany
    {
        return $this->belongsToMany(Local::class);
    }

    /**
     * Defines the relationship with the Tenant linked to this lease.
     *
     * This association connects the lease with the specific tenant entity
     * responsible for fulfilling its terms.
     *
     * @return BelongsTo<Tenant, covariant $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Polymorphic relationship to multiple Note instances associated with the lease.
     *
     * This allows the attachment of various notes to the lease, each serving as
     * distinct comments or records pertinent to the lease.
     *
     * @return MorphMany<Note, covariant $this>
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Belongs-to relationship with the Invoice model associated with the lease.
     *
     * This links a specific financial document (invoice) to the lease, facilitating
     * monetary transactions and records.
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
     * Relationship with the Quotation model linked to the lease.
     *
     * This association is used to connect a quotation or proposal, often acting
     * as the precursor to formalizing the lease terms.
     *
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
     * Specifies the attribute casting for the model, converting attributes to specific types.
     *
     * This includes enums and date fields to ensure the correct format and integrity.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'risk_accessment_label' => RiskLevel::class,
            'status' => LeaseStatus::class,
            'arrival_date' => 'datetime',
            'departure_date' => 'datetime',
        ];
    }

    /**
     * Provides a computed attribute that represents the lease period.
     *
     * Compiles the 'arrival_date' and 'departure_date' into a single string, indicating
     * the duration of the lease.
     *
     * @return Attribute<non-falsy-string, never>
     */
    protected function period(): Attribute
    {
        return Attribute::get(fn(): string => "{$this->arrival_date->format('d/m/Y H:i')} - {$this->departure_date->format('d/m/Y H:i')}");
    }
}
