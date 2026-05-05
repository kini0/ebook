<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Développement personnel', 'icon' => 'sparkles'],
            ['name' => 'Business & Entrepreneuriat', 'icon' => 'briefcase'],
            ['name' => 'Finance personnelle', 'icon' => 'banknote'],
            ['name' => 'Marketing digital', 'icon' => 'megaphone'],
            ['name' => 'Technologie & Programmation', 'icon' => 'code'],
            ['name' => 'Santé & Bien-être', 'icon' => 'heart'],
            ['name' => 'Romans & Fiction', 'icon' => 'book-open'],
        ];

        foreach ($rows as $i => $row) {
            Category::updateOrCreate(
                ['slug' => Str::slug($row['name'])],
                [
                    'name'      => $row['name'],
                    'icon'      => $row['icon'],
                    'position'  => $i,
                    'is_active' => true,
                ]
            );
        }
    }
}
