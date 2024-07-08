<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
