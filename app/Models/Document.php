<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\DocumentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a document associated with a lease in the system.
 *
 * This model is observed by the DocumentObserver for lifecycle events
 * and maintains a relationship to the User model, identifying the document creator.
 *
 * @property int                              $id          The unique identifier from the document in the database storage.
 * @property int                              $user_id     The unique identifier from the user who saved the document in the application.
 * @property int                              $lease_id    The unique identifier for the associated lease with the document.
 * @property string                           $name        The name of the document that has been stored.
 * @property string                           $attachment  The file path of the document that has been stored.
 * @property \Illuminate\Support\Carbon|null  $created_at  The timestamp indicating when the document has been stored in the application.
 * @property \Illuminate\Support\Carbon|null  $updated_at  The Timestamp indicating when the document has been updated for the last time.
 *
 * @package App\Models
 */
#[ObservedBy(DocumentObserver::class)]
class Document extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Get the user who created the document.
     *
     * This defines an inverse one-to-many relationship with the User model,
     * where 'user_id' is the foreign key on the Document model.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
