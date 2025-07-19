<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'role_id',
        'invited_by',
        'token',
        'expires_at',
        'accepted_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isExpired()
    {
        return $this->expires_at < now();
    }

    public function isValid()
    {
        return !$this->is_used && !$this->isExpired();
    }

    public function markAsUsed()
    {
        $this->update([
            'is_used' => true,
            'accepted_at' => now(),
        ]);
    }

    public function getStatusText()
    {
        if ($this->is_used) {
            return 'Aceptada';
        }

        if ($this->isExpired()) {
            return 'Expirada';
        }

        return 'Pendiente';
    }

    public function getStatusBadgeClass()
    {
        if ($this->is_used) {
            return 'bg-green-100 text-green-800';
        }

        if ($this->isExpired()) {
            return 'bg-red-100 text-red-800';
        }

        return 'bg-yellow-100 text-yellow-800';
    }

    public static function generateToken()
    {
        return Str::random(64);
    }

    public function scopeValid($query)
    {
        return $query->where('is_used', false)
                    ->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }
}
