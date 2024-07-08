<?php

declare(strict_types=1);

namespace App\DataObjects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

/**
 * Class FeedbackSubmissionDataObject
 *
 * This data object represents the feedback submission data.
 */
final class FeedbackSubmissionDataObject extends Data
{
    /**
     * Construct a new FeedbackSubmissionDataObject.
     *
     * This constructor initializes the feedback submission data object with the provided subject and message.
     * The MapInputName attribute is used to map the input names to the property names.
     *
     * @param  string  $subject  The subject of the feedback, mapped from 'onderwerp'.
     * @param  string  $message  The message of the feedback, mapped from 'feedback'.
     */
    public function __construct(
        #[MapInputName('onderwerp')]
        public readonly string $subject,
        #[MapInputName('feedback')]
        public readonly string $message,
    ) {}
}
