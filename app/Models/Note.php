<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\NoteObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
