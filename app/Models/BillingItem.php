<?php

declare(strict_types=1);

namespace App\Models;

use App\Filament\Resources\InvoiceResource\Enums\BillingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @var array<string, BillingType|int>
     */
    protected $attributes = [
        'type' => BillingType::BillingLine,
        'quantity' => 1,
    ];

    /**
     * Data relation for the creator of the invoice line.
     *
     * @return BelongsTo<User, self>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['type' => BillingType::class];
    }
}
