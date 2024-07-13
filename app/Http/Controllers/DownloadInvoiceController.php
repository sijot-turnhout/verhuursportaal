<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\Invoice;
use Spatie\LaravelPdf\PdfBuilder;

use function Spatie\LaravelPdf\Support\pdf;

final readonly class DownloadInvoiceController
{
    public function __invoke(Invoice $record): PdfBuilder
    {
        abort_if(auth()->user()->cannot('download-invoice', $record), 404);

        return pdf()
            ->view($this->getDocumentView($record), compact('record'))
            ->name($record->payment_reference . '.pdf');
    }

    private function getDocumentView(Invoice $record): string
    {
        if (InvoiceStatus::Quotation_Request === $record->status) {
            return 'pdfs.quota';
        }

        return 'pdfs.invoice';
    }
}
