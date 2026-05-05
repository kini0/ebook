<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Développement personnel', 'Business', 'Finance', 'Santé', 'Cuisine',
            'Romans', 'Entrepreneuriat', 'Marketing digital', 'Technologie',
        ]);

        return [
            'name'        => $name,
            'slug'        => Str::slug($name) . '-' . Str::random(4),
            'description' => $this->faker->sentence(12),
            'icon'        => 'book-open',
            'position'    => $this->faker->numberBetween(0, 50),
            'is_active'   => true,
        ];
    }
}
