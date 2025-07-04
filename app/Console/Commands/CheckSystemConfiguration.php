<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckSystemConfiguration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check system configuration and database status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando configuración del sistema...');
        $this->newLine();

        // Check database connection
        $this->checkDatabase();

        // Check tables
        $this->checkTables();

        // Check users and roles
        $this->checkUsersAndRoles();

        // Check middleware
        $this->checkMiddleware();

        // Check email configuration
        $this->checkEmailConfiguration();

        $this->newLine();
        $this->info('✅ Verificación completada!');
    }

    private function checkDatabase()
    {
        $this->info('📊 Verificando conexión a la base de datos...');

        try {
            DB::connection()->getPdo();
            $this->line('   ✅ Conexión a la base de datos: OK');
        } catch (\Exception $e) {
            $this->error('   ❌ Error de conexión: ' . $e->getMessage());
            return;
        }
    }

    private function checkTables()
    {
        $this->info('🗃️  Verificando tablas...');

        $requiredTables = [
            'users', 'roles', 'books', 'categories',
            'loans', 'invitations', 'jobs', 'failed_jobs'
        ];

        foreach ($requiredTables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $this->line("   ✅ Tabla '{$table}': OK ({$count} registros)");
            } else {
                $this->error("   ❌ Tabla '{$table}': NO EXISTE");
            }
        }
    }

    private function checkUsersAndRoles()
    {
        $this->info('👥 Verificando usuarios y roles...');

        try {
            $rolesCount = Role::count();
            $usersCount = User::count();
            $adminCount = User::whereHas('role', function($q) {
                $q->where('name', 'admin');
            })->count();

            $this->line("   ✅ Roles: {$rolesCount}");
            $this->line("   ✅ Usuarios: {$usersCount}");
            $this->line("   ✅ Administradores: {$adminCount}");

            if ($adminCount === 0) {
                $this->warn('   ⚠️  No hay usuarios administradores');
            }

        } catch (\Exception $e) {
            $this->error('   ❌ Error verificando usuarios: ' . $e->getMessage());
        }
    }

    private function checkMiddleware()
    {
        $this->info('🛡️  Verificando middleware...');

        $middlewareFile = app_path('Http/Middleware/EnsureUserIsActive.php');

        if (file_exists($middlewareFile)) {
            $this->line('   ✅ Middleware EnsureUserIsActive: OK');
        } else {
            $this->error('   ❌ Middleware EnsureUserIsActive: NO EXISTE');
        }

        // Check if middleware is registered
        $this->line('   ⚠️  Verifica manualmente si EnsureUserIsActive está en $routeMiddleware o en middlewareGroups del Kernel.');


        if (isset($middlewareGroups['web'])) {
            $this->line('   ✅ Grupo de middleware web: OK');
        }
    }

    private function checkEmailConfiguration()
    {
        $this->info('📧 Verificando configuración de email...');

        $mailDriver = config('mail.default');
        $mailHost = config('mail.mailers.smtp.host');
        $mailPort = config('mail.mailers.smtp.port');
        $mailUsername = config('mail.mailers.smtp.username');

        $this->line("   📮 Driver: {$mailDriver}");
        $this->line("   🏠 Host: {$mailHost}");
        $this->line("   🔌 Puerto: {$mailPort}");
        $this->line("   👤 Usuario: " . ($mailUsername ? '✅ Configurado' : '❌ No configurado'));

        if (empty($mailUsername)) {
            $this->warn('   ⚠️  Configuración de email incompleta');
        }
    }
}
