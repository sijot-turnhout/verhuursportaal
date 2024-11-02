<?php

namespace App\Models;

use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Deposit extends Model
{
    protected $guarded = ['id'];

    protected $attributes = ['status' => DepositStatus::Unpaid];

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'refund_at' => 'datetime',
        ];
    }
}
