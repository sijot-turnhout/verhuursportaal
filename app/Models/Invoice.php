<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Eloquent\FinancialAssistance;
use App\Filament\Clusters\Billing\Resources\InvoiceResource\States;
use App\Filament\Clusters\Billing\Resources\InvoiceResource\States\InvoiceStateContract;
use App\Filament\Resources\InvoiceResource\Enums\BillingType;
use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use InvalidArgumentException;

/**
 * Class Invoice
 *
 * @property int                              $id                 The Unique identifier from the invoice in the database
 * @property string                           $payment_reference  The reference number for the invoice
 * @property InvoiceStatus                    $status             The status from the invoice in the application storage.
 * @property int                              $creator_id         The unique identifier from the application user who created the invoice.
 * @property int                              $lease_id           The unique identifier from the lease that is attached to the invoice.
 * @property int                              $customer_id        The unique identifier from the customer (tenant) that is attached to the invoice.
 * @property string                           $description        The extra information that will be placed on the invoice.
 * @property \Illuminate\Support\Carbon|null  $due_at             The timestamp that indicates when the invoice is due.
 * @property \Illuminate\Support\Carbon|null  $paid_at            The timestamp that indicates when the invoice is registered as paid.
 * @property \Illuminate\Support\Carbon|null  $cancelled_at       The timestamp that indicates when the invoice is registered as cancelled
 * @property \Illuminate\Support\Carbon|null  $created_at         The timestamp that indicates when the record has been added to the database.
 * @property \Illuminate\Support\Carbon|null  $updated_at         The timestamp that indicates when the record has updated for the last time.
 *
 * @method static uncollectibleInvoices() The collection of invoices that are registered as uncollected.
 */
final class Invoice extends Model implements FinancialAssistance
{
    /**
     * The attributes that are protected from the mass assignment system.
     *
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * Method for defining default values to the declared attributes in the array.
     *
     * @var array<string, object|int|string>
     */
    protected $attributes = [
        'status' => InvoiceStatus::Draft,
    ];

    /**
     * Boot the model and set up event listeners for the Invoice lifecycle.
     *
     * This method defines a `creating` event listener to automatically
     * generate a unique payment reference when a new Invoice is being created.
     *
     * The payment reference format is: `{current year}-{incremental number}`
     * - The incremental number is determined based on the last invoice's payment reference.
     * - If there is no previous invoice, the number starts at 1.
     *
     * Example:
     * - Last payment reference: `2024-000123`
     * - New payment reference: `2024-000124`
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        self::creating(function ($invoice): void {
            $lastInvoice = self::orderBy('id', 'desc')->first();
            $lastNumber = $lastInvoice ? (int) mb_substr($lastInvoice->payment_reference, -6) : 0;
            $invoice->payment_reference = date('Y') . '-' . mb_str_pad((string) ($lastNumber + 1), 6, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Data relation for all the invoice lines that are registered to an invoice.
     *
     * @return MorphMany<BillingItem, covariant $this>
     */
    public function invoiceLines(): MorphMany
    {
        return $this->morphMany(BillingItem::class, 'billingdocumentable');
    }

    /**
     * Data relation for the lease where the invoice is attached to.
     *
     * @return BelongsTo<Lease, covariant $this>
     */
    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    /**
     * Calpculpate the total billable amount for the invoice.
     *
     * This method creates a custom Eloquent attribute that computes the
     * billable total by subtracting the discount total from the subtotal.
     *
     * @return Attribute<int|float, never-return> Custom attribute for the billable amount.
     */
    public function billableTotal(): Attribute
    {
        /**
         * Using the Attribute class, define a getter that calculates
         * the billable total dynamically.
         *
         * @phpstan-ignore-next-line The ignored line ensures static analysis does not flag this.
         */
        return Attribute::get(fn(): int|float => $this->getSubTotal() - $this->getDiscountTotal());
    }

    /**
     * Get the total value of all discounts applied to the invoice.
     *
     * This method queries the invoice lines with the type 'Discount'
     * and sums up their 'total_price' values.
     *
     * @return int|float|string The total discount value as an integer, float, or string.
     */
    public function getDiscountTotal(): int|float|string
    {
        return $this->invoiceLines()->where('type', BillingType::Discount)->sum('total_price');
    }

    /**
     * Get the subtotal of all billable items in the invoice.
     *
     * This method queries the invoice lines with the type 'BillingLine'
     * and sums up their `total_price` values.
     *
     * @return int|float|string The subtotal value as an integer, float, or string.
     */
    public function getSubTotal(): int|float|string
    {
        return $this->invoiceLines()->where('type', BillingType::BillingLine)->sum('total_price');
    }

    /**
     * Data relation for the tenant (customer) that will be billed for the lease;
     *
     * @return BelongsTo<Tenant, covariant $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'customer_id');
    }

    /**
     * Data relation for the user that created the invoice in the database storage.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Returns an appropriate state object based on the current invoice status.
     *
     * This method leverages a `match` expression to determine the correct state class
     * for the invoice. Each state class corresponds to a particular invoice status,
     * such as Draft, Open, Paid, Void, or Uncollected. The returned state object
     * implements the `InvoiceStateContract` interface, allowing for state-specific
     * behavior and transitions.
     *
     * @return InvoiceStateContract      The state object corresponding to the current invoice status.
     * @throws InvalidArgumentException  If the status does not match any known state.
     */
    public function state(): InvoiceStateContract
    {
        return match ($this->status) {
            InvoiceStatus::Draft => new States\DraftInvoiceState($this),
            InvoiceStatus::Open => new States\OpenInvoiceState($this),
            InvoiceStatus::Paid => new States\PaidInvoiceState($this),
            InvoiceStatus::Void => new States\VoidInvoiceState($this),
            InvoiceStatus::Uncollected => new States\UncollectedInvoiceState($this),
        };
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, class-string|string>
     */
    protected function casts(): array
    {
        return [
            'due_at' => 'date',
            'quotation_due_at' => 'date',
            'status' => InvoiceStatus::class,
        ];
    }
}
