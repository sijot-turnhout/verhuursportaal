<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreContactSubmission;
use App\Http\Requests\StoreContactSubmissionRequest;
use Illuminate\Http\RedirectResponse;
use Spatie\LaravelData\Exceptions\InvalidDataClass;

final readonly class ContactController
{
    /**
     * Process the contact form submission.
     *
     * This method handles the complete process for contact form submissions by:
     * - Validating and mapping the incoming request data using the StoreContactSubmissionRequest.
     * - Executing the StoreContactSubmission action to save the mapped data in the backend systems.
     * - Displaying a success flash message to inform the user that their message has been received.
     * - Redirecting the user to the front page.
     *
     * Each step is executed in a clear sequence to ensure that the contact form data is properly validated,
     * stored, and that the user receives immediate feedback on the operation.
     *
     * @param StoreContactSubmissionRequest $contactSubmissionRequest The request object that validates and maps the contact form data.
     * @param StoreContactSubmission        $storeContactSubmission The action responsible for storing the contact form data.
     *
     * @return RedirectResponse The response that redirects the user to the front page.
     *
     * @throws InvalidDataClass If the data mapping fails due to an invalid data structure.
     */
    public function __invoke(StoreContactSubmissionRequest $contactSubmissionRequest, StoreContactSubmission $storeContactSubmission): RedirectResponse
    {
        $storeContactSubmission->execute($contactSubmissionRequest->getData());
        flash('We hebben je vraag en of opmerking goed ontvangen. We gaan er zo snel mogelijk mee aan de slag', 'alert-success');

        return redirect()->action(FrontPageController::class);
    }
}
