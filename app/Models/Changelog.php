<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Changelog
 *
 * The changelog model represents a record (list) of changes or updates on the locals and inventory items in the system.
 * It includes information about follow-ups and the user responsible for those follow-ups.
 */
final class Changelog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'title', 'description'];

    /**
     * Defines the relationship bewteen the changelog and the User models.
     * This indicates that each changelog is associated with a user who is responsible for the follow-ups related to the changelog.
     *
     * @return BelongsTo  The relationship definition.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
