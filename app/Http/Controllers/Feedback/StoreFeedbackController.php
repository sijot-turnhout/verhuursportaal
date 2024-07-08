<?php

declare(strict_types=1);

namespace App\Http\Controllers\Feedback;

use App\Actions\StoreFeedbackSubmission;
use App\Http\Requests\FeedbackSubmissionRequest;
use App\Models\Lease;
use Illuminate\Http\RedirectResponse;
use Spatie\LaravelData\Exceptions\InvalidDataClass;

/**
 * Class StoreFeedbackController
 *
 * This controller handles the storing of feedback submissions.
 */
final readonly class StoreFeedbackController
{
    /**
     * Handle the incoming feedback submission request.
     *
     * This method processes the feedback submission request, stores the feedback,
     * flashes a success message, and redirects to the welcome page.
     *
     * @param  FeedbackSubmissionRequest  $feedbackSubmissionRequest  The request containing feedback submission data.
     * @param  Lease  $lease  The lease model instance associated with the feedback.
     * @param  StoreFeedbackSubmission  $storeFeedbackSubmission  The action responsible for storing feedback.
     * @return RedirectResponse A response that redirects to the welcome route.
     *
     * @throws InvalidDataClass
     */
    public function __invoke(
        FeedbackSubmissionRequest $feedbackSubmissionRequest,
        Lease $lease,
        StoreFeedbackSubmission $storeFeedbackSubmission,
    ): RedirectResponse {
        $storeFeedbackSubmission->handle($feedbackSubmissionRequest->getData(), $lease);
        flash('We hebben je feedback met success opgeslagen. Bedankt voor je feedback!', 'alert-success');

        return redirect()->route('welcome');
    }
}
