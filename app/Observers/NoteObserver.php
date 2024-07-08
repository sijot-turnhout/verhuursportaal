<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Note;

final readonly class NoteObserver
{
    public function created(Note $note): void
    {
        $note->author()->associate(auth()->user())->save();
    }
}
