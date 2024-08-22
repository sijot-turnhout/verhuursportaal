<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\IssueBuilder;
use App\Filament\Resources\LocalResource\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Issue
 *
 * @property int                              $id           The unique identifier from the issue ticket in the database.
 * @property int                              $creator_id   The unique identifier from the user who created the issue ticket.
 * @property int                              $user_id      The unique identifier from the user who is assigned to the issue ticket.
 * @property Status                           $status       The current registered status of the issue ticket.
 * @property string                           $title        The title of the issue ticket.
 * @property string                           $description  The description of the issue ticket.
 * @property \Illuminate\Support\Carbon|null  $created_at   The timestamp from when the record has been created in the database?
 * @property \Illuminate\Support\Carbon|null  $updated_at   The timestamp from when the record has been updated for the last time.
 *
 * @method markAsClosed()
 */
class Issue extends Model
{
    use HasFactory;

    /**
     * The database columns that are protected in the mass-assignment system from the Laravel framework.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Method to declare the default attributes in the database record.
     *
     * @var array<string, object|int|string>
     */
    protected $attributes = [
        'status' => Status::Open,
    ];

    /**
     * The data relation that registers the creator of the issue ticket to the record.
     *
     * @return BelongsTo<User, self>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the parent issueable model (related to this changelog) in a polymorphic relationship.
     *
     * This method defines a polymorphic relationship, allowing the `Changelog` model to be associated with multiple other models.
     * The related model can be of any type that is designated as "issueable".
     *
     * @return MorphTo The polymorphic relationship to the parent issueable model.
     */
    public function issueable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The data relation for the user that is assigned to the given issue ticket.
     *
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a new Eloquent query builder instance for the model.
     *
     * This method overrides the default Eloqeunt builder with a custom 'IssueBuilder' specifically designed for the model.
     * It provides extended functionality tailored to the needs of the 'Changelog' model or associated logic.
     *
     * @param \Illuminate\Database\Query\Builder $query  The base query builder instance.
     * @return IssueBuilder<self>                        A new instance of the custom 'IssueBuilder' specific to this model.
     */
    public function newEloquentBuilder($query): IssueBuilder
    {
        return new IssueBuilder($query);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['status' => Status::class];
    }
}
