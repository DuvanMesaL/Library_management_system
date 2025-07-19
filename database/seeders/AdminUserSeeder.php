<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $bibliotecarioRole = Role::where('name', 'bibliotecario')->first();
        $lectorRole = Role::where('name', 'lector')->first();

        // Crear usuario administrador
        User::firstOrCreate(
            ['email' => 'admin@biblioteca.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario bibliotecario
        User::firstOrCreate(
            ['email' => 'bibliotecario@biblioteca.com'],
            [
                'name' => 'Bibliotecario',
                'password' => Hash::make('password'),
                'role_id' => $bibliotecarioRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario lector
        User::firstOrCreate(
            ['email' => 'lector@biblioteca.com'],
            [
                'name' => 'Usuario Lector',
                'password' => Hash::make('password'),
                'role_id' => $lectorRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
