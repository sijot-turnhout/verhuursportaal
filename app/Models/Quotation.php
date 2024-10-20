<?php

namespace App\Models;

use App\Enums\QuotationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

final class Quotation extends Model
{
    use HasFactory;

    /**
     * The attributes that are protected from the mass assignment system.
     *
     * @var string[]
     */
    protected $guarded = ['id'];

    protected $attributes = [
        'status' => QuotationStatus::Draft,
    ];

    public function quotationLines(): MorphMany
    {
        return $this->morphMany(BillingItem::class, 'billingdocumentable');
    }

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    public function reciever(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'reciever_id');
    }

    /**
     * @todo check if we can register this to an job action class
     */
    public static function boot(): void
    {
        parent::boot();

        self::creating(function ($quotation): void {
            $lastQuotation = self::orderBy('id', 'desc')->first();
            $lastNumber = $lastQuotation ? (int) mb_substr($lastQuotation->payment_reference, -6) : 0;
            $quotation->reference = date('Y') . '-' . mb_str_pad((string) ($lastNumber + 1), 6, '0', STR_PAD_LEFT);
        });
    }

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
