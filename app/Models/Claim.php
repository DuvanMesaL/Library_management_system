<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loan_id',
        'type',
        'subject',
        'description',
        'status',
        'priority',
        'assigned_to',
        'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    public function isClosed()
    {
        return $this->status === 'closed';
    }

    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'in_progress':
                return 'bg-blue-100 text-blue-800';
            case 'resolved':
                return 'bg-green-100 text-green-800';
            case 'closed':
                return 'bg-gray-100 text-gray-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    public function getStatusText()
    {
        switch ($this->status) {
            case 'pending':
                return 'Pendiente';
            case 'in_progress':
                return 'En progreso';
            case 'resolved':
                return 'Resuelto';
            case 'closed':
                return 'Cerrado';
            default:
                return 'Desconocido';
        }
    }

    public function getPriorityBadgeClass()
    {
        switch ($this->priority) {
            case 'low':
                return 'bg-green-100 text-green-800';
            case 'medium':
                return 'bg-yellow-100 text-yellow-800';
            case 'high':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    public function getPriorityText()
    {
        switch ($this->priority) {
            case 'low':
                return 'Baja';
            case 'medium':
                return 'Media';
            case 'high':
                return 'Alta';
            default:
                return 'Normal';
        }
    }

    public function getTypeText()
    {
        switch ($this->type) {
            case 'book_damage':
                return 'Daño del libro';
            case 'book_lost':
                return 'Libro perdido';
            case 'extension_request':
                return 'Solicitud de extensión';
            case 'complaint':
                return 'Queja';
            case 'suggestion':
                return 'Sugerencia';
            case 'other':
                return 'Otro';
            default:
                return 'General';
        }
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
