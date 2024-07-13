<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DataObjects\ContactSubmissionData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class StoreContactSubmissionRequest extends FormRequest
{
    /**
     * @use WithData<ContactSubmissioNData>
     */
    use WithData;

    protected string $dataClass = ContactSubmissionData::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

        ];
    }
}
