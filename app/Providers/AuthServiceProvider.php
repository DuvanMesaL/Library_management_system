<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Claim;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate para gestionar libros (crear, editar, eliminar)
        Gate::define('manage-books', function (User $user) {
            return $user->isAdmin() || $user->isBibliotecario();
        });

        // Gate para gestionar préstamos
        Gate::define('manage-loans', function (User $user) {
            return $user->isAdmin() || $user->isBibliotecario();
        });

        // Gate para gestionar usuarios (solo admin)
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        // Gate para gestionar invitaciones (solo admin)
        Gate::define('manage-invitations', function (User $user) {
            return $user->isAdmin();
        });

        // Gate para gestionar reclamaciones
        Gate::define('manage-claims', function (User $user) {
            return $user->isAdmin() || $user->isBibliotecario();
        });

        // Gate para ver reportes
        Gate::define('view-reports', function (User $user) {
            return $user->isAdmin() || $user->isBibliotecario();
        });

        // Gate para solicitar préstamos
        Gate::define('request-loans', function (User $user) {
            return $user->isActive();
        });

        // Gate para crear reclamaciones
        Gate::define('create-claims', function (User $user) {
            return $user->isActive();
        });
    }
}
