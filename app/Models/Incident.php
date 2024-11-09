<?php

namespace App\Models;

use App\Enums\IncidentCodes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Incident extends Model
{
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lease(): HasOne
    {
        return $this->hasOne(Lease::class);
    }

    protected function casts(): array
    {
        return ['incident_code' => IncidentCodes::class];
    }
}
