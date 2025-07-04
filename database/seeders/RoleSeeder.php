<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrador con acceso completo al sistema'
            ],
            [
                'name' => 'bibliotecario',
                'description' => 'Gestiona libros, préstamos y devoluciones'
            ],
            [
                'name' => 'lector',
                'description' => 'Consulta catálogo y solicita préstamos'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
