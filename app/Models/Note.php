<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\NoteObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class note
 *
 * @property int                         $id             The unique identifier from the note in the database column.
 * @property int                         $author_id      The unique identifier from the user that created the note
 * @property string                      $noteable_type  The class namespace for the record that is related in this model.
 * @property int                         $noteable_id    The unique identifier for the record that is related to the 'noteable_type'
 * @property string                      $title          The title of the note.
 * @property string                      $body           The actual note in the database
 * @property \Illuminate\Support\Carbon  $created_at     The timestamp that indicates when the note is created.
 * @property \Illuminate\Support\Carbon  $updated_at     The timestamp that indicates when the note is edited for the last time.
 */
#[ObservedBy(NoteObserver::class)]
final class Note extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    /**
     * @return MorphTo<\Illuminate\Database\Eloquent\Model, self>
     */
    public function noteable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo<User, self>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
