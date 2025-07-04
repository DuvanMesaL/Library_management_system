<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $ficcionId = Category::where('name', 'Ficción')->first()->id;
        $noFiccionId = Category::where('name', 'No Ficción')->first()->id;
        $tecnologiaId = Category::where('name', 'Tecnología')->first()->id;
        $historiaId = Category::where('name', 'Historia')->first()->id;

        $books = [
            [
                'title' => 'Cien años de soledad',
                'author' => 'Gabriel García Márquez',
                'isbn' => '9780060883287',
                'category_id' => $ficcionId,
                'description' => 'Obra maestra del realismo mágico que narra la historia de la familia Buendía',
                'publication_year' => 1967,
                'copies_total' => 3,
                'copies_available' => 3
            ],
            [
                'title' => 'Don Quijote de la Mancha',
                'author' => 'Miguel de Cervantes',
                'isbn' => '9788424116378',
                'category_id' => $ficcionId,
                'description' => 'Clásico de la literatura española sobre las aventuras del ingenioso hidalgo',
                'publication_year' => 1605,
                'copies_total' => 2,
                'copies_available' => 2
            ],
            [
                'title' => 'Sapiens: De animales a dioses',
                'author' => 'Yuval Noah Harari',
                'isbn' => '9780062316097',
                'category_id' => $noFiccionId,
                'description' => 'Breve historia de la humanidad desde la Edad de Piedra hasta la era digital',
                'publication_year' => 2011,
                'copies_total' => 4,
                'copies_available' => 4
            ],
            [
                'title' => 'Clean Code: Manual de desarrollo ágil de software',
                'author' => 'Robert C. Martin',
                'isbn' => '9780132350884',
                'category_id' => $tecnologiaId,
                'description' => 'Guía para escribir código limpio y mantenible',
                'publication_year' => 2008,
                'copies_total' => 2,
                'copies_available' => 2
            ],
            [
                'title' => 'El arte de la guerra',
                'author' => 'Sun Tzu',
                'isbn' => '9788497940849',
                'category_id' => $historiaId,
                'description' => 'Tratado militar chino sobre estrategia y táctica',
                'publication_year' => 0500,
                'copies_total' => 3,
                'copies_available' => 3
            ],
            [
                'title' => 'Laravel: Up & Running',
                'author' => 'Matt Stauffer',
                'isbn' => '9781492041207',
                'category_id' => $tecnologiaId,
                'description' => 'Guía completa para el desarrollo con Laravel',
                'publication_year' => 2019,
                'copies_total' => 2,
                'copies_available' => 2
            ]
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
