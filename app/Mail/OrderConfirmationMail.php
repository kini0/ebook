<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from:    new Address(config('mail.from.address'), config('mail.from.name')),
            subject: "Confirmation de votre commande — {$this->order->reference}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-confirmation',
            with: [
                'order' => $this->order,
                'url'   => route('customer.orders.show', $this->order),
            ]
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        if ($this->order->invoice && $this->order->invoice->pdf_path) {
            $disk = Storage::disk(config('filesystems.default', 'local'));
            if ($disk->exists($this->order->invoice->pdf_path)) {
                $attachments[] = Attachment::fromStorageDisk(config('filesystems.default'), $this->order->invoice->pdf_path)
                    ->as("{$this->order->invoice->number}.pdf")
                    ->withMime('application/pdf');
            }
        }
        return $attachments;
    }
}
