<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserGroup;
use App\Filament\Support\Concerns\Ownership;
use App\Observers\UserObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property int                         $id                 The unique identifier from the user in the database storage
 * @property string                      $name               The full name of the user in the application
 * @property UserGroup                   $user_group         The enumeration that declares the user group from the user account
 * @property string                      $email              The email adres from the user in the application
 * @property \Illuminate\Support\Carbon  $email_verified_at  The timestamp for when the user is verified in the application
 * @property string                      $phone_number       The phone number for the user in the application.
 * @property string                      $password           The authentication password for the user
 * @property string                      $last_login_ip      The IP address from the login location from the user
 * @property string                      $remember_token     The unique remember token from the user login in the application
 * @property string                      $last_seen_at       The timestamp from when the user is last authenticated in the application
 * @property \Illuminate\Support\Carbon  $created_at         The timestamp for when the record in created in the application
 * @property \Illuminate\Support\Carbon  $updated_at         The timestamp for when the user is last edited in the application
 */
#[ObservedBy(UserObserver::class)]
final class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use Ownership;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_group',
        'phone_number',
        'last_login_ip',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return auth()->check();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
            'user_group' => UserGroup::class,
        ];
    }
}
