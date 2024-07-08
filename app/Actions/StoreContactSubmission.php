<?php

declare(strict_types=1);

namespace App\Actions;

use App\DataObjects\ContactSubmissionData;
use App\Models\ContactSubmission;
use Illuminate\Support\Facades\DB;

/**
 * Class ContactSubmission
 *
 * @todo 2024-05-05: Discuss we need to implement an attachment check for the email address to connect the submission to an existing tenant.
 */
final readonly class StoreContactSubmission
{
    /**
     * Method for processing the contact form data into the storage systems of the application.
     *
     * @param  ContactSubmissionData $contactSubmissionData  The data value object that contains the contact form data
     * @return void
     */
    public function execute(ContactSubmissionData $contactSubmissionData): void
    {
        DB::transaction(function () use ($contactSubmissionData): void {
            ContactSubmission::query()->create(attributes: $contactSubmissionData->toArray());
        });
    }
}
