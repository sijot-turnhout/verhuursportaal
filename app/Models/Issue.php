<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\IssueBuilder;
use App\Filament\Resources\LocalResource\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Status $status
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
