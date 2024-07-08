<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DataObjects\FeedbackSubmissionDataObject;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

/**
 * FeedbackSubmissionRequest class.
 *
 * This class represents the request object for submitting feedback. It extends Laravel's FormRequest
 * and uses Spatie's WithData trait to bind and validate data using the FeedbackSubmissionDataObject.
 */
final class FeedbackSubmissionRequest extends FormRequest
{
    /** @use WithData<FeedbackSubmissionDataObject> */
    use WithData;

    /**
     * The data class used to store and validate feedback submission data.
     *
     * @var string
     */
    protected string $dataClass = FeedbackSubmissionDataObject::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * These rules define the validation logic for incoming feedback submission requests. It ensures
     * that the 'onderwerp' field is required, must be a string, and cannot exceed 255 characters,
     * while 'feedback' is also required and must be a string.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'onderwerp' => ['required', 'string', 'max:255'],
            'feedback' => ['required', 'string'],
        ];
    }
}
