<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\ReservationConfirmation;
use Cog\Contracts\Ban\Bannable as BannableInterface;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\Deprecated;

/**
 * Class Tenant
 *
 * @property string                          $name          The full name of the tenant. Virually generated bases on the firstName and LastName.
 * @property string                          $firstName     The first name of the tenant that is stored in the database
 * @property string                          $lastName      The last name of the tenant that is stored in the database
 * @property string                          $email         The email address of the tenant in the application
 * @property string|null                     $phone_number  The phone number of the tenant in the application.
 * @property string|null                     $address       The billing address for the tenant in the application.
 * @property \Illuminate\Support\Carbon|null $banned_at     The timestamp that indicates when the tenant is marked as 'bannad tenant' in the application.
 * @property \Illuminate\Support\Carbon      $created_at    The timestamp that indicated when the record has been created in the database.
 * @property \Illuminate\Support\Carbon      $updated_at    The timestamp that indicates when the record has been updated for the last time.
 */
final class Tenant extends Model implements BannableInterface
{
    use Bannable;

    /** @use hasFactory<\Database\Factories\TenantFactory> */
    use HasFactory;
    use Notifiable;

    /**
     *
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Data relation for all the leases that are attached to the tenant.
     *
     * @return HasMany<Lease, covariant $this>
     */
    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }

    /**
     * Data relation for the notes that are attached to the tenant.
     *
     * @return MorphMany<Note, covariant $this>
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }
    /**
     * @return HasMany<Incident, covariant $this>
     */
    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    /**
     * Method for sending out the reservation request confirmation to the tenant.
     *
     * @param  Lease $lease The entiry from the lease reservation that has been sotred in them application.
     * @return void
     */
    public function sendOutReservationConfirmation(Lease $lease): void
    {
        $this->notify((new ReservationConfirmation($lease))->afterCommit());
    }

    /**
     * Attribute cast to get the full name of the tenant
     *
     * @todo We need to investigate if we can remove this attribute
     *
     * @return Attribute<non-falsy-string, never>
     */
    #[Deprecated(reason: 'Deprecated in favor of using the mysql virtual columns', since: '1.0')]
    protected function fullName(): Attribute
    {
        return Attribute::get(fn(): string => "{$this->firstName} {$this->lastName}");
    }

    /**
     * Attribute to determine whether the tenant is blacklisted in the application database of not.
     *
     * @return Attribute<bool, never-return>
     */
    protected function isBlacklisted(): Attribute
    {
        return Attribute::get(fn(): bool => $this->isBanned());
    }
}
