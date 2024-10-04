<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Models\Feedback;
use App\Features\Feedback as FeedbackFeatureFlag;
use App\Notifications\FeedbackNotification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Laravel\Pennant\Feature;

/**
 * Trait HasFeedbackSupport
 *
 * This trait provides feedback-related functionalities for a model.
 */
trait HasFeedbackSupport
{
    /**
     * Define a relationship to the Feedback model.
     *
     * @return BelongsTo<Feedback, self> The relationship to the Feedback model.
     */
    public function feedback(): BelongsTo
    {
        return $this->belongsTo(Feedback::class);
    }

    /**
     * Send a feedback notification to the tenant.
     *
     * This method marks feedback as requested, then sends a feedback notification to the tenant
     * with a validity period specified by $validUntil.
     *
     * @param  Carbon  $validUntil  The timestamp until the feedback request is valid.
     */
    public function sendFeedbackNotification(Carbon $validUntil): void
    {
        $this->tenant->notify(new FeedbackNotification($validUntil, $this));
        $this->markAsFeedbackRequested($validUntil);
    }

    /**
     * Determine if a feedback notification can be sent.
     *
     * This method checks if feedback does not already exist, if feedback_valid_until is null,
     * if the lease status is finalized, and if the feedback feature is enabled.
     *
     * @return bool True if a feedback notification can be sent, false otherwise.
     */
    public function canSendOutFeedbackNotification(): bool
    {
        return ($this->feedback()->doesntExist() && null === $this->feedback_valid_until)
            && Feature::active(FeedbackFeatureFlag::class);
    }

    /**
     * Mark the feedback as requested.
     *
     * This method sets the feedback_valid_until attribute to the provided timestamp and saves the model.
     *
     * @param  Carbon  $validUntil  The timestamp until the feedback request is valid.
     * @return self                 The current instance of the model.
     */
    public function markAsFeedbackRequested(Carbon $validUntil): self
    {
        $this->feedback_valid_until = $validUntil;
        $this->save();

        return $this;
    }

    /**
     * Mark the feedback as registered.
     *
     * This method nullifies the feedback_valid_until attribute and saves the model.
     *
     * @return self The current instance of the model.
     */
    public function markAsFeedbackRegistered(): self
    {
        $this->feedback_valid_until = null;
        $this->save();

        return $this;
    }
}
