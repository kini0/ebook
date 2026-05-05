<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateForOrder(Order $order): Invoice
    {
        $invoice = $order->invoice ?? Invoice::create([
            'number'         => $this->nextNumber(),
            'order_id'       => $order->id,
            'user_id'        => $order->user_id,
            'subtotal_cents' => $order->subtotal_cents,
            'tax_cents'      => $order->tax_cents,
            'total_cents'    => $order->total_cents,
            'currency'       => $order->currency,
            'issue_date'     => now()->toDateString(),
        ]);

        $pdfPath = $this->renderPdf($invoice->fresh(['order.items.ebook', 'order.user']));
        $invoice->update(['pdf_path' => $pdfPath]);

        return $invoice->refresh();
    }

    public function renderPdf(Invoice $invoice): string
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'order'   => $invoice->order,
            'items'   => $invoice->order->items,
            'user'    => $invoice->order->user,
            'company' => [
                'name'    => \App\Models\Setting::get('company_name', config('app.name')),
                'address' => \App\Models\Setting::get('company_address', ''),
                'taxid'   => \App\Models\Setting::get('company_taxid', ''),
                'email'   => \App\Models\Setting::get('support_email', config('mail.from.address')),
                'phone'   => \App\Models\Setting::get('support_phone', ''),
            ],
        ]);

        $relativePath = "invoices/{$invoice->number}.pdf";
        Storage::disk(config('filesystems.default', 'local'))->put($relativePath, $pdf->output());

        return $relativePath;
    }

    public function nextNumber(): string
    {
        $year   = now()->format('Y');
        $count  = Invoice::whereYear('created_at', $year)->count() + 1;
        return sprintf('INV-%s-%05d', $year, $count);
    }

    public function streamPdf(Invoice $invoice): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $disk = Storage::disk(config('filesystems.default', 'local'));
        if (! $invoice->pdf_path || ! $disk->exists($invoice->pdf_path)) {
            $this->renderPdf($invoice);
        }
        return $disk->download($invoice->pdf_path, "{$invoice->number}.pdf");
    }
}
