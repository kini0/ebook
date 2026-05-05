<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EbookResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'slug'              => $this->slug,
            'subtitle'          => $this->subtitle,
            'author'            => $this->author,
            'short_description' => $this->short_description,
            'description'       => $this->description,
            'category'          => $this->whenLoaded('category', fn () => [
                'id'   => $this->category?->id,
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ]),
            'language'          => $this->language,
            'pages'             => $this->pages,
            'price_cents'       => $this->price_cents,
            'compare_at_cents'  => $this->compare_at_cents,
            'formatted_price'   => $this->formatted_price,
            'cover_url'         => $this->cover_url,
            'is_featured'       => $this->is_featured,
            'published_at'      => $this->published_at?->toIso8601String(),
        ];
    }
}
