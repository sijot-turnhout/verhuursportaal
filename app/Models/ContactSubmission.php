<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContactMessageStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ContactSubmission
 *
 * @property int                             $id            The unique identifier (PK) from the record in the database storage
 * @property string                          $first_name    The first name of the sender
 * @property string                          $last_name     The last name of the sender
 * @property string                          $full_name     The full name of the sender composed as viatual data column based on the first and last name column in the database table.
 * @property string                          $email         The unique email address from the sender
 * @property string                          $phone_number  The phone number of the sender which we can contact him on
 * @property string                          $message       The question that is sended to us from the sender
 * @property \Illuminate\Support\Carbon|null $updated_at    The timestamp for when the record is last edited in the database storage
 * @property \Illuminate\Support\Carbon|null $created_at    The timestamp for when the record is created in the database storage.
 */
final class ContactSubmission extends Model
{
    /** @use HasFactory<\Database\Factories\ContactSubmissionFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'phone_number', 'message', 'status'];

    protected $attributes = ['status' => ContactMessageStatus::New];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ContactMessageStatus::class,
        ];
    }
}
