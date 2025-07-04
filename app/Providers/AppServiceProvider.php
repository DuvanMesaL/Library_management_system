<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot(): void
    {
        // Gate para invitar usuarios (solo admin)
        Gate::define('invite-users', function (User $user) {
            return $user->canInviteUsers();
        });

        // Gate para gestionar libros (admin y bibliotecario)
        Gate::define('manage-books', function (User $user) {
            return $user->canManageBooks();
        });

        // Gate para gestionar préstamos (admin y bibliotecario)
        Gate::define('manage-loans', function (User $user) {
            return $user->canManageLoans();
        });

        // Gate para acceso de administrador
        Gate::define('admin-access', function (User $user) {
            return $user->isAdmin();
        });

        // Gate para acceso de bibliotecario
        Gate::define('bibliotecario-access', function (User $user) {
            return $user->isBibliotecario() || $user->isAdmin();
        });
    }
}
