<?php

declare(strict_types=1);

namespace App\Models;

use App\Filament\Clusters\Billing\Resources\DepositResource\Enums\DepositStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property DepositStatus $status
 */
final class Deposit extends Model
{
    protected $guarded = ['id'];

    /**
     * @var array<string, DepositStatus>
     */
    protected $attributes = ['status' => DepositStatus::Paid];

    /**
     * @return BelongsTo<Lease, covariant $this>
     */
    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    protected function casts(): array
    {
        return [
            'status' => DepositStatus::class,
            'amount' => 'float',
            'paid_at' => 'datetime',
            'refund_at' => 'datetime',
        ];
    }
}
