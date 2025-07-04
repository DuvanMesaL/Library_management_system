<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $bibliotecarioRole = Role::where('name', 'bibliotecario')->first();
        $lectorRole = Role::where('name', 'lector')->first();

        // Crear usuario administrador
        User::create([
            'name' => 'Administrador Principal',
            'email' => 'admin@biblioteca.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);

        // Crear usuario bibliotecario de ejemplo
        User::create([
            'name' => 'María Bibliotecaria',
            'email' => 'bibliotecario@biblioteca.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role_id' => $bibliotecarioRole->id,
            'is_active' => true,
        ]);

        // Crear usuario lector de ejemplo
        User::create([
            'name' => 'Juan Lector',
            'email' => 'lector@biblioteca.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role_id' => $lectorRole->id,
            'is_active' => true,
        ]);
    }
}
