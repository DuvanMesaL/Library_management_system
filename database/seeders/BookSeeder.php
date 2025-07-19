<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener categorías
        $ficcionCategory = Category::where('name', 'Ficción')->first();
        $noFiccionCategory = Category::where('name', 'No Ficción')->first();
        $cienciaCategory = Category::where('name', 'Ciencia y Tecnología')->first();
        $historiaCategory = Category::where('name', 'Historia')->first();

        $books = [
            [
                'title' => 'Cien años de soledad',
                'author' => 'Gabriel García Márquez',
                'isbn' => '9780307474728',
                'category_id' => $ficcionCategory ? $ficcionCategory->id : null,
                'description' => 'Una obra maestra del realismo mágico que narra la historia de la familia Buendía a lo largo de siete generaciones en el pueblo ficticio de Macondo.',
                'publication_year' => 1967,
                'publisher' => 'Editorial Sudamericana',
                'pages' => 417,
                'language' => 'Español',
                'copies_total' => 5,
                'copies_available' => 5,
                'location' => 'Estante A-1',
                'is_active' => true,
            ],
            [
                'title' => 'Don Quijote de la Mancha',
                'author' => 'Miguel de Cervantes',
                'isbn' => '9788424116859',
                'category_id' => $ficcionCategory ? $ficcionCategory->id : null,
                'description' => 'La obra cumbre de la literatura española y una de las más importantes de la literatura universal. Narra las aventuras del ingenioso hidalgo.',
                'publication_year' => 1605,
                'publisher' => 'Francisco de Robles',
                'pages' => 863,
                'language' => 'Español',
                'copies_total' => 3,
                'copies_available' => 3,
                'location' => 'Estante A-2',
                'is_active' => true,
            ],
            [
                'title' => 'Sapiens: De animales a dioses',
                'author' => 'Yuval Noah Harari',
                'isbn' => '9788499926223',
                'category_id' => $noFiccionCategory ? $noFiccionCategory->id : null,
                'description' => 'Una breve historia de la humanidad desde la aparición del Homo sapiens hasta la actualidad.',
                'publication_year' => 2011,
                'publisher' => 'Debate',
                'pages' => 496,
                'language' => 'Español',
                'copies_total' => 4,
                'copies_available' => 4,
                'location' => 'Estante B-1',
                'is_active' => true,
            ],
            [
                'title' => 'El origen de las especies',
                'author' => 'Charles Darwin',
                'isbn' => '9788420649085',
                'category_id' => $cienciaCategory ? $cienciaCategory->id : null,
                'description' => 'La obra fundamental que estableció la teoría de la evolución por selección natural.',
                'publication_year' => 1859,
                'publisher' => 'John Murray',
                'pages' => 502,
                'language' => 'Español',
                'copies_total' => 2,
                'copies_available' => 2,
                'location' => 'Estante C-1',
                'is_active' => true,
            ],
            [
                'title' => 'Breve historia del tiempo',
                'author' => 'Stephen Hawking',
                'isbn' => '9788408031161',
                'category_id' => $cienciaCategory ? $cienciaCategory->id : null,
                'description' => 'Una introducción accesible a la cosmología y la física teórica moderna.',
                'publication_year' => 1988,
                'publisher' => 'Bantam Books',
                'pages' => 256,
                'language' => 'Español',
                'copies_total' => 3,
                'copies_available' => 3,
                'location' => 'Estante C-2',
                'is_active' => true,
            ],
            [
                'title' => 'El arte de la guerra',
                'author' => 'Sun Tzu',
                'isbn' => '9788497940849',
                'category_id' => $historiaCategory ? $historiaCategory->id : null,
                'description' => 'Tratado militar chino sobre estrategia y táctica militar.',
                'publication_year' => -500,
                'publisher' => 'Antigua China',
                'pages' => 180,
                'language' => 'Español',
                'copies_total' => 3,
                'copies_available' => 3,
                'location' => 'Estante D-1',
                'is_active' => true,
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9780132350884',
                'category_id' => $cienciaCategory ? $cienciaCategory->id : null,
                'description' => 'Manual de desarrollo ágil de software. Guía para escribir código limpio y mantenible.',
                'publication_year' => 2008,
                'publisher' => 'Prentice Hall',
                'pages' => 464,
                'language' => 'Español',
                'copies_total' => 2,
                'copies_available' => 2,
                'location' => 'Estante C-3',
                'is_active' => true,
            ],
            [
                'title' => 'Laravel: Up & Running',
                'author' => 'Matt Stauffer',
                'isbn' => '9781492041207',
                'category_id' => $cienciaCategory ? $cienciaCategory->id : null,
                'description' => 'Guía completa para el desarrollo web con Laravel PHP framework.',
                'publication_year' => 2019,
                'publisher' => "O'Reilly Media",
                'pages' => 550,
                'language' => 'Inglés',
                'copies_total' => 2,
                'copies_available' => 2,
                'location' => 'Estante C-4',
                'is_active' => true,
            ],
        ];

        foreach ($books as $bookData) {
            Book::firstOrCreate(
                ['isbn' => $bookData['isbn']],
                $bookData
            );
        }
    }
}
