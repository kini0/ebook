<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoices) {}

    public function show(Invoice $invoice, Request $request)
    {
        abort_unless($invoice->user_id === $request->user()->id, 403);
        return view('customer.invoices.show', [
            'invoice' => $invoice->load(['order.items.ebook']),
        ]);
    }

    public function download(Invoice $invoice, Request $request)
    {
        abort_unless($invoice->user_id === $request->user()->id, 403);
        return $this->invoices->streamPdf($invoice);
    }
}
