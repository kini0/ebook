<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Ebook;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Ebook>
 */
class EbookFactory extends Factory
{
    public function definition(): array
    {
        $title = ucfirst($this->faker->unique()->sentence(4, false));
        $price = $this->faker->numberBetween(2000, 25000); // 2 000 → 25 000 FCFA

        return [
            'category_id'        => Category::factory(),
            'title'              => $title,
            'slug'               => Str::slug($title) . '-' . Str::random(4),
            'subtitle'           => $this->faker->sentence(6),
            'author'             => $this->faker->name(),
            'short_description'  => $this->faker->sentence(20),
            'description'        => $this->faker->paragraphs(5, true),
            'isbn'               => $this->faker->isbn13(),
            'language'           => 'fr',
            'pages'              => $this->faker->numberBetween(80, 420),
            'price_cents'        => $price,
            'compare_at_cents'   => $this->faker->boolean(60) ? $price + $this->faker->numberBetween(2000, 8000) : null,
            'cover_path'         => 'ebooks/covers/placeholder.jpg',
            'file_path'          => 'ebooks/files/placeholder.pdf',
            'file_format'        => 'pdf',
            'file_size_bytes'    => $this->faker->numberBetween(500_000, 8_000_000),
            'is_featured'        => $this->faker->boolean(20),
            'is_published'       => true,
            'published_at'       => now()->subDays($this->faker->numberBetween(0, 180)),
        ];
    }

    public function unpublished(): self
    {
        return $this->state(['is_published' => false, 'published_at' => null]);
    }

    public function featured(): self
    {
        return $this->state(['is_featured' => true]);
    }
}
