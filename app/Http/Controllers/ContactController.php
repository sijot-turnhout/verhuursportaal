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
     * Method for processing the contact form into the application.
     *
     * @param StoreContactSubmissionRequest $contactSubmissionRequest The class that handles the form validation and maps it to an Value object
     * @param StoreContactSubmission $storeContactSubmission The class that is responsible for storing the contact form data into the backend systems.
     * @return RedirectResponse
     *
     * @throws InvalidDataClass
     */
    public function __invoke(StoreContactSubmissionRequest $contactSubmissionRequest, StoreContactSubmission $storeContactSubmission): RedirectResponse
    {
        $storeContactSubmission->execute($contactSubmissionRequest->getData());
        flash('We hebben je vraag en of opmerking goed ontvangen. We gaan er zo snel mogelijk mee aan de slag', 'alert-success');

        return redirect()->action(FrontPageController::class);
    }
}
