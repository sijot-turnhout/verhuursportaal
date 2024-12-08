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
    /**
     * The attributes that are not mass assignable.
     *
     * This property protects the specified attributes from mass assignment,
     * ensuring they cannot be directly set via methods like 'create' or 'fill'.
     *
     * In this case, the 'id' field is guarded to prevent accidental or malicious
     * modification of the primary key
     *
     * @var array<int, string> An array pf attribute names to guard.
     */
    protected $guarded = ['id'];

    /**
     * The default attributes for the model.
     *
     * This property is used to set default values for the model's attributes when a new instance is created.
     * The attributes defined here will be automatically assigned to the model is no other value is provided.
     *
     * - 'type' is set to a default value of 'BillingType::BillingLine' representing the type of billing
     *    (an enum constant from the 'BillingType' enumeration)
     * - 'quantity' is set to a default value of '1', indicating that by default, the invoice line has a qty of 1.
     *
     * returns: An associative array where the keys are attribute names and the values are their default values.
     *
     * @var array<string, BillingType|int>
     */
    protected $attributes = [
        'type' => BillingType::BillingLine,
        'quantity' => 1,
    ];

    /**
     * Relationship to the user who created the invoice line.
     *
     * This defines a `BelongsTo` relationship between the invoice line and the
     * user who created it, based on the `creator_id` foreign key.
     *
     * @return BelongsTo<User, covariant $this> A `BelongsTo` relationship instance.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Polymorphic relationship for the billing documentable entity.
     *
     *  This defines a `MorphTo` relationship, allowing the invoice line
     *  to associate with different types of billing document entities.
     *
     * @return MorphTo<Model, covariant $this> A `MorphTo` relationship instance.
     */
    public function billingdocumentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relationship to the associated invoice.
     *
     * This defines a 'BelongsTo' relationship between the invoice line and its parent invoice,
     * based on the default 'invoice_id' foreign key.
     *
     * @return BelongsTo<Invoice, covariant $this> A `BelongsTo` relationship for the associated invoice.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the attributes that should be cast to specific types.
     *
     * This method returns an array mapping attribute names to the type or class
     * they should be cast when accessed or saved in the database.
     *
     * @return array<string, string> An associative array of attribute casts.
     */
    protected function casts(): array
    {
        return ['type' => BillingType::class];
    }
}
