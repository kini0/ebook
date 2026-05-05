<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'reference'      => $this->reference,
            'status'         => $this->status?->value,
            'subtotal_cents' => $this->subtotal_cents,
            'tax_cents'      => $this->tax_cents,
            'total_cents'    => $this->total_cents,
            'currency'       => $this->currency,
            'payment_method' => $this->payment_method,
            'placed_at'      => $this->placed_at?->toIso8601String(),
            'paid_at'        => $this->paid_at?->toIso8601String(),
            'items'          => $this->whenLoaded('items', fn () => $this->items->map(fn ($i) => [
                'ebook_id'         => $i->ebook_id,
                'title'            => $i->title_snapshot,
                'author'           => $i->author_snapshot,
                'unit_price_cents' => $i->unit_price_cents,
                'quantity'         => $i->quantity,
                'total_cents'      => $i->total_cents,
            ])),
            'invoice'        => $this->whenLoaded('invoice', fn () => $this->invoice ? [
                'number'        => $this->invoice->number,
                'total_cents'   => $this->invoice->total_cents,
                'pdf_available' => (bool) $this->invoice->pdf_path,
            ] : null),
        ];
    }
}
