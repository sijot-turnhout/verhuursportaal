<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Feedback
 *
 * @property int                              $id          The unique identifier from the feedback message in the database.
 * @property string                           $subject     The subject of the feedback message that is provided by the customer (tenant).
 * @property string                           $message     The feedback message that is provided by the customer (tenant).
 * @property \Illuminate\Support\Carbon|null  $created_at  The timestamp from when the record has been created in the database.
 * @property \Illuminate\Support\Carbon|null  $updated_at  The timestamp from when the record has been updated in the database.
 */
final class Feedback extends Model
{
    use HasFactory;

    /**
     * The database columns that are protected from the internal mass-assignment system.
     *
     * @var string[]
     */
    protected $guarded = ['id'];
}
