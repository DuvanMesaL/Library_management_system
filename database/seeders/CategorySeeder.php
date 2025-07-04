<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Ficción',
                'description' => 'Novelas y cuentos de ficción'
            ],
            [
                'name' => 'No Ficción',
                'description' => 'Libros informativos y educativos'
            ],
            [
                'name' => 'Ciencia',
                'description' => 'Libros científicos y técnicos'
            ],
            [
                'name' => 'Historia',
                'description' => 'Libros de historia y biografías'
            ],
            [
                'name' => 'Tecnología',
                'description' => 'Libros sobre tecnología y programación'
            ],
            [
                'name' => 'Arte',
                'description' => 'Libros sobre arte y cultura'
            ],
            [
                'name' => 'Filosofía',
                'description' => 'Libros de filosofía y pensamiento'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
