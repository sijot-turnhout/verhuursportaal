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

/**
 * @property string $firstName The first name of the tenant that is stored in the database
 * @property string $lastName  The last name of the tenant that is stored in the database
 */
final class Tenant extends Model implements BannableInterface
{
    use Bannable;
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
     * @return HasMany<Lease>
     */
    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }

    /**
     * Data relation for the notes that are attached to the tenant.
     *
     * @return MorphMany<Note>
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Method for sending out the reservation request confirmation to the tenant.
     *
     * @return void
     */
    public function sendOutReservationConfirmation(): void
    {
        $this->notify((new ReservationConfirmation())->afterCommit());
    }

    /**
     * Attribute cast to get the full name of the tenant
     *
     * @return Attribute<string, never>
     */
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
