<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Eloquent\FinancialAssistance;
use App\Enums\QuotationStatus;
use App\Filament\Clusters\Billing\Resources\QuotationResource\States;
use App\Filament\Clusters\Billing\Resources\QuotationResource\States\QuotationStateContract;
use App\Filament\Resources\InvoiceResource\Enums\BillingType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Quotation
 *
 * Represents a quotation within the billing system.
 *
 * This model encapsulates the data and behaviors associated with a quotation, including its current status, associated items, lease information, and receiver details.
 * Quotation status can transition through various states such as Draft, Open, Accepted, Declined, and Expired.
 * The state design pattern is employed to handle state-specific behaviors and transitions cleanly and effectively.
 *
 * @property QuotationStatus $status
 *
 * @package App\Models
 */
final class Quotation extends Model implements FinancialAssistance
{
    /**
     * The attributes that are protected from the mass assignment system.
     *
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * The default attributes for a new quotation instance.
     *
     * The status is set to Draft by default, indicating that the quotation is not yet finalized or sent for approval.
     * This allows for further modifications before moving to the next state.
     *
     * @var array<string, QuotationStatus>
     */
    protected $attributes = [
        'status' => QuotationStatus::Draft,
    ];

    /**
     * Boot the model and set up event listeners for its lifecycle.
     *
     * This method overrides the default boot method of the Model class to establish custom behavior during the creation of a quotation.
     * Specifically, it generates a unique reference number for each new quotation based on the last existing quotation's reference.
     * The reference format is `YYYY-XXXXXX`, where `XXXXXX` is a zero-padded sequential number.
     *
     * @todo Consider if we can register this functionality with a job action class
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        self::creating(function ($quotation): void {
            $lastQuotation = self::orderBy('id', 'desc')->first();
            $lastNumber = $lastQuotation ? (int) mb_substr($lastQuotation->reference, -6) : 0;
            $quotation->reference = date('Y') . '-' . mb_str_pad((string) ($lastNumber + 1), 6, '0', STR_PAD_LEFT);
        });
    }

    public function billableTotal(): Attribute
    {
        /** @phpstan-ignore-next-line */
        return Attribute::get(fn(): int|float => $this->getSubTotal() ?? 0 - $this->getDiscountTotal() ?? 0);
    }

    public function getDiscountTotal(): int|float|string
    {
        return $this->quotationLines()->where('type', BillingType::Discount)->sum('total_price');
    }

    public function getSubTotal(): int|float|string
    {
        return $this->quotationLines()->where('type', BillingType::BillingLine)->sum('total_price');
    }

    /**
     * Retrieve the quotation lines associated with the quotation.
     *
     * This method establishes a one-to-many polymorphic relationship between the Quotation model and its associated BillingItem models.
     * Quotation lines detail the items or services included in the quotation.
     *
     * @return MorphMany<BillingItem, covariant $this>
     */
    public function quotationLines(): MorphMany
    {
        return $this->morphMany(BillingItem::class, 'billingdocumentable');
    }

    /**
     * Retrieve the lease associated with the quotation.
     *
     * This method defines a many-to-one relationship, linking the quotation to a specific Lease model.
     * This allows for easy retrieval of the lease information related to this quotation.
     *
     * @return BelongsTo<Lease, covariant $this>
     */
    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    /**
     * Retrieve the creator associated with the quotation.
     *
     * This method defines a belongs-to relationsip, linking the user account that has created the quotation to the quotation.
     * This allows for easy retrieval of the user information relation to this quotation.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Retrieve the receiver associated with the quotation.
     *
     * This method establishes a many-to-one relationship between the quotation and the Tenant model.
     * It identifies the tenant who will receive or has been quoted for the items/services in this quotation.
     *
     * @return BelongsTo<Tenant, covariant $this>
     */
    public function reciever(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'reciever_id');
    }

    /**
     * Get the current state of the quotation based on its status.
     *
     * This method checks the current status of the quotation and returns the corresponding state object,
     * which encapsulates the behavior and transitions allowed for that status. The states define what actions
     * can be taken on the quotation based on its lifecycle stage.
     *
     * @return QuotationStateContract
     */
    public function state(): QuotationStateContract
    {
        return match ($this->status) {
            QuotationStatus::Draft => new States\DraftQuotationState($this),
            QuotationStatus::Open => new States\OpenQuotationState($this),
            QuotationStatus::Accepted => new States\AcceptedQuotationState($this),
            QuotationStatus::Declined => new States\DeclinedQuotationState($this),
            QuotationStatus::Expired => new States\ExpiredQuotationState($this),
        };
    }

    /**
     * The attributes that should be cast to native types for proper handling.
     *
     * This method specifies the types for specific attributes in the model.
     * Casting attributes to their appropriate types ensures that data is correctly formatted and accessible within the application.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => QuotationStatus::class,
            'expires_at' => 'date',
            'approved_at' => 'date',
            'rejected_at' => 'date',
            'signed_at' => 'date',
        ];
    }
}
