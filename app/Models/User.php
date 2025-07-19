<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'phone',
        'address',
        'date_of_birth',
        'emergency_contact',
        'emergency_phone',
        'notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Relación con el rol del usuario
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relación con los préstamos del usuario
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Relación con las reclamaciones del usuario
     */
    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * Relación con las reclamaciones asignadas al usuario
     */
    public function assignedClaims()
    {
        return $this->hasMany(Claim::class, 'assigned_to');
    }

    /**
     * Relación con las invitaciones enviadas por el usuario
     */
    public function sentInvitations()
    {
        return $this->hasMany(Invitation::class, 'invited_by');
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    /**
     * Verificar si el usuario es bibliotecario
     */
    public function isBibliotecario(): bool
    {
        return $this->role && $this->role->name === 'bibliotecario';
    }

    /**
     * Verificar si el usuario es lector
     */
    public function isLector(): bool
    {
        return $this->role && $this->role->name === 'lector';
    }

    /**
     * Verificar si el usuario está activo
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Verificar si el usuario puede gestionar libros
     */
    public function canManageBooks(): bool
    {
        return $this->isAdmin() || $this->isBibliotecario();
    }

    /**
     * Verificar si el usuario puede gestionar préstamos
     */
    public function canManageLoans(): bool
    {
        return $this->isAdmin() || $this->isBibliotecario();
    }

    /**
     * Verificar si el usuario puede gestionar usuarios
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Verificar si el usuario puede gestionar reclamaciones
     */
    public function canManageClaims(): bool
    {
        return $this->isAdmin() || $this->isBibliotecario();
    }

    /**
     * Verificar si el usuario puede ver reportes
     */
    public function canViewReports(): bool
    {
        return $this->isAdmin() || $this->isBibliotecario();
    }

    /**
     * Verificar si el usuario puede enviar invitaciones
     */
    public function canSendInvitations(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Obtener préstamos activos del usuario
     */
    public function activeLoans()
    {
        return $this->loans()->where('status', 'active');
    }

    /**
     * Obtener préstamos vencidos del usuario
     */
    public function overdueLoans()
    {
        return $this->loans()->where('status', 'active')
                            ->where('due_date', '<', now());
    }

    /**
     * Verificar si el usuario tiene préstamos vencidos
     */
    public function hasOverdueLoans(): bool
    {
        return $this->overdueLoans()->exists();
    }

    /**
     * Obtener el número de préstamos activos
     */
    public function getActiveLoansCount(): int
    {
        return $this->activeLoans()->count();
    }

    /**
     * Obtener el número de préstamos vencidos
     */
    public function getOverdueLoansCount(): int
    {
        return $this->overdueLoans()->count();
    }

    /**
     * Obtener el nombre del rol
     */
    public function getRoleName(): string
    {
        return $this->role ? $this->role->name : 'Sin rol';
    }

    /**
     * Obtener el nombre del rol en español
     */
    public function getRoleDisplayName(): string
    {
        if (!$this->role) {
            return 'Sin rol';
        }

        return match($this->role->name) {
            'admin' => 'Administrador',
            'bibliotecario' => 'Bibliotecario',
            'lector' => 'Lector',
            default => ucfirst($this->role->name),
        };
    }

    /**
     * Scope para usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para usuarios por rol
     */
    public function scopeByRole($query, $roleName)
    {
        return $query->whereHas('role', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }
}
