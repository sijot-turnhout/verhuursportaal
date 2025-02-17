<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\LaravelPdf\PdfBuilder;

use function Spatie\LaravelPdf\Support\pdf;

final readonly class DownloadDocumentController
{
    public function invoice(Request $request, Invoice $record): PdfBuilder
    {
        abort_if(boolean: $request->user()->cannot('download-invoice', $record), code: Response::HTTP_NOT_FOUND);

        return pdf()
            ->view('pdfs.invoice', compact('record'))
            ->margins(top: 10, bottom: 10)
            ->name($record->payment_reference . '.pdf');
    }

    /**
     * @todo Build up this function
     *
     * @param Request $request
     * @param Quotation $record
     * @return PdfBuilder
     */
    public function quotation(Request $request, Quotation $record): PdfBuilder
    {
        return pdf()
            ->view('pdfs.quota', compact('record'))
            ->name($record->reference . '.pdf');
    }
}
