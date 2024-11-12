<?php

declare(strict_types=1);

namespace App\Models;

use App\Filament\Resources\InvoiceResource\Enums\BillingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class BillingItem
 *
 * @property int                              $id            The unique identifier from the billing line in the database.
 * @property int                              $invoice_id    The unique identifier from the invoice where the billing line is attached to.
 * @property int                              $type          The type of the billing line. Can be an invoice line or discount line.
 * @property string                           $name          The name of the item/service that will be billed to the customer.
 * @property int                              $quantity      The quantity from the item or service that is billed to the customer (tenant).
 * @property float                            $unit_price    The unit price of the service or item that is billed.
 * @property float                            $total_price   The total price of the billing line, calculated bases on the unit price and quantity
 * @property \Illuminate\Support\Carbon|null  $created_at    The timestamp for when the record is created in the database?
 * @property \Illuminate\Support\Carbon|null  $updated_at    The timestamp from when the record is last edited in the database.
 */
final class BillingItem extends Model
{
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
     * @return BelongsTo<User, covariant $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * @return MorphTo<Model, covariant $this>
     */
    public function billingdocumentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo<Invoice, covariant $this>
     */
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
