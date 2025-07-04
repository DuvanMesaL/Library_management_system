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
        // Ensure roles exist
        $adminRole = Role::firstOrCreate(['name' => 'admin'], [
            'description' => 'Administrador del sistema'
        ]);

        $bibliotecarioRole = Role::firstOrCreate(['name' => 'bibliotecario'], [
            'description' => 'Bibliotecario'
        ]);

        $lectorRole = Role::firstOrCreate(['name' => 'lector'], [
            'description' => 'Lector'
        ]);

        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@biblioteca.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create bibliotecario user
        User::firstOrCreate(
            ['email' => 'bibliotecario@biblioteca.com'],
            [
                'name' => 'Bibliotecario',
                'password' => Hash::make('biblio123'),
                'role_id' => $bibliotecarioRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create lector user
        User::firstOrCreate(
            ['email' => 'lector@biblioteca.com'],
            [
                'name' => 'Lector',
                'password' => Hash::make('lector123'),
                'role_id' => $lectorRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuarios de prueba creados exitosamente:');
        $this->command->info('Admin: admin@biblioteca.com / admin123');
        $this->command->info('Bibliotecario: bibliotecario@biblioteca.com / biblio123');
        $this->command->info('Lector: lector@biblioteca.com / lector123');
    }
}
