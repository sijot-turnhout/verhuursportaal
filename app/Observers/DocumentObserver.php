<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

/**
 * Observer for Document lifecycle events, focused on file management for attachment cleanup.
 *
 * This observer manages attachment storage by deleting associated files when a Document
 * is deleted or when its attachment is updated. It ensures that storage remains free
 * of orphaned or outdated files, improving file management and data integrity.
 *
 * @package App\Observers
 */
final readonly class DocumentObserver
{
    /**
     * Triggered upon the "deleted" event of a Document instance.
     *
     * When a Document is deleted, this method verifies if an attachment file exists
     * and removes it from the storage. This prevents storage clutter by ensuring
     * that attachments for deleted Document records don’t remain in the filesystem.
     *
     * @param  Document $document The Document instance that is being deleted.
     * @return void
     */
    public function deleted(Document $document): void
    {
        if (null !== $document->attachment) {
            Storage::disk('local')->delete($document->attachment);
        }
    }

    /**
     * Triggered on the "updated" event of a Document instance.
     *
     * This method handles cases where a Document’s attachment file is changed.
     * When the attachment attribute is modified, it deletes the old file from storage,
     * retaining only the most recent file. This approach ensures storage efficiency
     * by discarding outdated attachments that are no longer associated with the Document.
     *
     * Conditions:
     * - Checks if the 'attachment' field was modified (using `isDirty('attachment')`).
     * - Ensures the previous attachment file is non-null to avoid accidental deletions.
     *
     * @param  Document $document The Document instance that has been updated.
     * @return void
     */
    public function updated(Document $document): void
    {
        if ($document->isDirty('attachment') && null !== $document->getOriginal('attachment')) {
            Storage::disk('local')->delete($document->getOriginal('attachment'));
        }
    }
}
