<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Filament\Resources\InvoiceResource\Enums\InvoiceStatus;
use App\Models\Invoice;
use Spatie\LaravelPdf\PdfBuilder;

use function Spatie\LaravelPdf\Support\pdf;

final readonly class DownloadDocumentController
{
    public function invoice(Invoice $record): PdfBuilder
    {
        abort_if(auth()->user()->cannot('download-invoice', $record), 404);

        return pdf()
            ->view('pdfs.invoice', compact('record'))
            ->name($record->payment_reference . '.pdf');
    }
}
