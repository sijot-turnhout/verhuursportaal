<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Local extends Model
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
