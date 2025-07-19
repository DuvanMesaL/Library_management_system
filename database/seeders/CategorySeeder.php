<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Ficción',
                'description' => 'Novelas, cuentos y obras de ficción literaria',
                'is_active' => true,
            ],
            [
                'name' => 'No Ficción',
                'description' => 'Biografías, ensayos, libros de autoayuda y desarrollo personal',
                'is_active' => true,
            ],
            [
                'name' => 'Ciencia y Tecnología',
                'description' => 'Libros sobre ciencias, tecnología, programación y matemáticas',
                'is_active' => true,
            ],
            [
                'name' => 'Historia',
                'description' => 'Libros de historia, biografías históricas y documentos históricos',
                'is_active' => true,
            ],
            [
                'name' => 'Arte y Cultura',
                'description' => 'Libros sobre arte, música, pintura, escultura y cultura general',
                'is_active' => true,
            ],
            [
                'name' => 'Filosofía',
                'description' => 'Obras filosóficas, pensamiento crítico y reflexiones',
                'is_active' => true,
            ],
            [
                'name' => 'Educación',
                'description' => 'Libros educativos, manuales de estudio y material académico',
                'is_active' => true,
            ],
            [
                'name' => 'Literatura Infantil',
                'description' => 'Cuentos, fábulas y libros para niños y jóvenes',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
