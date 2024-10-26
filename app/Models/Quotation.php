<?php

namespace App\Models;

use App\Enums\QuotationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Clusters\Billing\Resources\QuotationResource\States;
use App\Filament\Clusters\Billing\Resources\QuotationResource\States\QuotationStateContract;
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
 * @package App\Models
 */
final class Quotation extends Model
{
    use HasFactory;

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
     * @var array
     */
    protected $attributes = [
        'status' => QuotationStatus::Draft,
    ];

    /**
     * Retrieve the quotation lines associated with the quotation.
     *
     * This method establishes a one-to-many polymorphic relationship between the Quotation model and its associated BillingItem models.
     * Quotation lines detail the items or services included in the quotation.
     *
     * @return MorphMany
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
     * @return BelongsTo
     */
    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

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
     * @return BelongsTo
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
        return match($this->status) {
            QuotationStatus::Draft => new States\DraftQuotationState($this),
            QuotationStatus::Open => new States\OpenQuotationState($this),
            QuotationStatus::Accepted => new States\AcceptedQuotationState($this),
            QuotationStatus::Declined => new States\DeclinedQuotationState($this),
            QuotationStatus::Expired => new States\ExpiredQuotationState($this),
        };
    }

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

    /**
     * The attributes that should be cast to native types for proper handling.
     *
     * This method specifies the types for specific attributes in the model.
     * Casting attributes to their appropriate types ensures that data is correctly formatted and accessible within the application.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'status' => QuotationStatus::class,
            'expires_at' => 'date',
            'approved_at' => 'date',
            'rejected_at' => 'date',
        ];
    }
}
