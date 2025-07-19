<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrador del sistema con acceso completo',
            ],
            [
                'name' => 'bibliotecario',
                'description' => 'Bibliotecario con permisos de gestión de libros y préstamos',
            ],
            [
                'name' => 'lector',
                'description' => 'Usuario lector con permisos básicos',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
