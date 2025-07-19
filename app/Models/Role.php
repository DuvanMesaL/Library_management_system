<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public static function findByName($name)
    {
        return static::where('name', $name)->first();
    }

    public function isAdmin()
    {
        return $this->name === 'admin';
    }

    public function isBibliotecario()
    {
        return $this->name === 'bibliotecario';
    }

    public function isLector()
    {
        return $this->name === 'lector';
    }

    public function getPermissions()
    {
        switch ($this->name) {
            case 'admin':
                return [
                    'manage-books',
                    'manage-loans',
                    'manage-users',
                    'manage-invitations',
                    'manage-claims',
                    'view-reports',
                    'request-loans',
                    'create-claims'
                ];
            case 'bibliotecario':
                return [
                    'manage-books',
                    'manage-loans',
                    'manage-claims',
                    'view-reports',
                    'request-loans',
                    'create-claims'
                ];
            case 'lector':
                return [
                    'request-loans',
                    'create-claims'
                ];
            default:
                return [];
        }
    }

    public function hasPermission($permission)
    {
        return in_array($permission, $this->getPermissions());
    }
}
