<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Local
 *
 * @property int                              $id                The unique identifier from the facility in the database
 * @property bool                             $storage_location. The indicator that indicates if the facility is a storage location
 * @property string                           $description       The extra information of description of the facility in the application.
 * @property \Illuminate\Support\Carbon|null  $created_at        The timestamp that indicates when the record is created in the database.
 * @property \Illuminate\Support\Carbon|null  $updated_at        The timestamp that indicates when the record has been updated for the last time in the database.
 */
final class Local extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'storage_location'];

    protected $casts = [
        'storage_location' => 'boolean',
    ];

    /**
     * The data relation for getting all the issue tickets (werkpunten) that are associated with the local (lokaal).
     *
     * @return HasMany<Issue>
     */
    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }
}
