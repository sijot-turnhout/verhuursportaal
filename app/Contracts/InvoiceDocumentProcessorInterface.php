<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Invoice;

interface InvoiceDocumentProcessorInterface
{
    public function handle(): Invoice;
}
