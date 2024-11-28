<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Local
 *
 * @property int                              $id                The unique identifier from the facility in the database
 * @property int                              $issuable_id       The unique identifier from the related record in the database storage.
 * @property string                           $issueable_type    The class FCN of the model that is related to the model.
 * @property bool                             $storage_location  The indicator that indicates if the facility is a storage location
 * @property string                           $description       The extra information of description of the facility in the application.
 * @property \Illuminate\Support\Carbon|null  $created_at        The timestamp that indicates when the record is created in the database.
 * @property \Illuminate\Support\Carbon|null  $updated_at        The timestamp that indicates when the record has been updated for the last time in the database.
 */
final class Local extends Model
{
    /** @use HasFactory<\Database\Factories\LocalFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'description', 'storage_location',
    ];

    /**
     * The data relation for getting all the issue tickets (werkpunten) that are associated with the local (lokaal).
     *
     * @return MorphMany<Issue, covariant $this>
     */
    public function issues(): MorphMany
    {
        return $this->morphMany(Issue::class, 'issueable');
    }

    /**
     * Defines a relationship where a single user can have multiple keys associated with them.
     * This allows easy access to all keys held by a specific user.
     *
     * @return HasMany
     */
    public function keyManagement(): HasMany
    {
        return $this->hasMany(Key::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'storage_location' => 'boolean',
        ];
    }
}
