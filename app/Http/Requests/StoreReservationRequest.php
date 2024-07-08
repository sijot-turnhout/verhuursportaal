<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DataObjects\ReservationDataObject;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class StoreReservationRequest extends FormRequest
{
    /**
     * @use WithData<ReservationDataObject>
     */
    use WithData;

    protected string $dataClass = ReservationDataObject::class;

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'aankomst' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_datum'],
            'vertrek' => ['required', 'date', 'date_format:Y-m-d', 'after:today'],
            'groep' => ['required', 'string', 'max:255'],
            'aantal_personen' => ['required'],
            'voornaam' => ['required', 'string', 'max:255'],
            'achternaam' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ];
    }
}
