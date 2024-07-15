<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\IssueBuilder;
use App\Filament\Resources\LocalResource\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Issue
 *
 * @property int                              $id           The unique identifier from the issue ticket in the database.
 * @property int                              $local_id     The unique identifier from the facility that is attached to the issue ticket.
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
     * The data relation for the user that is assigned to the given issue ticket.
     *
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return IssueBuilder<self>
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
