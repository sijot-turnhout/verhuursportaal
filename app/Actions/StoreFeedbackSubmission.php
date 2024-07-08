<?php

declare(strict_types=1);

namespace App\Actions;

use App\DataObjects\FeedbackSubmissionDataObject;
use App\Models\Feedback;
use App\Models\Lease;
use Illuminate\Support\Facades\DB;

/**
 * StoreFeedbackSubmission class.
 *
 * This class handles the storage of feedback submissions and associates
 * the feedback with a lease.
 */
final readonly class StoreFeedbackSubmission
{
    /**
     * Handle the feedback submission process.
     *
     * This method takes a FeedbackSubmissionDataObject and a Lease instance,
     * stores the feedback, associates it with the lease, and marks the lease
     * as having registered feedback. All actions are performed within a
     * database transaction.
     *
     * @param  FeedbackSubmissionDataObject $dataObject  The data object containing feedback submission details.
     * @param  Lease                        $lease       The lease instance to associate the feedback with.
     * @return void
     */
    public function handle(FeedbackSubmissionDataObject $dataObject, Lease $lease): void
    {
        DB::transaction(function () use ($dataObject, $lease): void {
            $feedbackSubmission = Feedback::query()->create($dataObject->toArray());

            $lease->feedback()->associate($feedbackSubmission)->save();
            $lease->markAsFeedbackRegistered();
        });
    }
}
