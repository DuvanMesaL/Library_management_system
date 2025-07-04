<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'role_id',
        'invited_by',
        'token',
        'status',
        'expires_at',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

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
        return $this->expires_at < Carbon::now();
    }

    public function isPending()
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    public static function generateToken()
    {
        return Str::random(64);
    }

    public function markAsAccepted()
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => Carbon::now(),
        ]);
    }

    public function markAsExpired()
    {
        $this->update([
            'status' => 'expired',
        ]);
    }
}
