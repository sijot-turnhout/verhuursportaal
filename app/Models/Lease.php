<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\LeaseBuilder;
use App\Concerns\HasFeedbackSupport;
use App\Concerns\HasUtilityMetrics;
use App\Enums\LeaseStatus;
use App\Observers\LeaseObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property LeaseStatus                     $status                The current status of the lease in the storage backend.
 * @property int                             $persons               The amount of persons in the group/organisation of the tenant.
 * @property \Illuminate\Support\Carbon      $departure_date        The date that the tenant will depart from the domain and the lease contract ends
 * @property \Illuminate\Support\Carbon      $arrival_date          The date that the lease will start and the tenant will arrive at the domain.
 * @property \Illuminate\Support\Carbon|null $feedback_valid_until
 * @property mixed $tenant
 * @property Invoice $invoice
 */
#[ObservedBy(LeaseObserver::class)]
final class Lease extends Model
{
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
     * The data relation for getting the user information from the user who performs the follow-up of the lease.
     *
     * @return BelongsTo<User, self>
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The data relation for the locals (lokalen) that are attached to the given lease.
     *
     * @return BelongsToMany<Local>
     */
    public function locals(): BelongsToMany
    {
        return $this->belongsToMany(Local::class);
    }

    /**
     * Data relation for getting the information about the tenant that is attached to the lease.
     *
     * @return BelongsTo<Tenant, self>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Data relation for getting the notes that are attached to the lease.
     *
     * @return MorphMany<Note>
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Data relation for getting the invoice that is attached to the lease.
     *
     * @return BelongsTo<Invoice, self>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
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
