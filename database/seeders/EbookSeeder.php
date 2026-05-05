<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ebook;
use Illuminate\Database\Seeder;

class EbookSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return;
        }

        Ebook::factory()
            ->count(24)
            ->state(fn () => ['category_id' => $categories->random()->id])
            ->create();

        Ebook::factory()
            ->count(6)
            ->featured()
            ->state(fn () => ['category_id' => $categories->random()->id])
            ->create();
    }
}
