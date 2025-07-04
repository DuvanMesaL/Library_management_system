<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'user_id',
        'subject',
        'description',
        'status',
        'priority',
        'assigned_to',
        'admin_response',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    public function markAsResolved($response = null, $assignedBy = null)
    {
        $this->update([
            'status' => 'resolved',
            'admin_response' => $response,
            'assigned_to' => $assignedBy,
            'resolved_at' => Carbon::now(),
        ]);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_review' => 'bg-blue-100 text-blue-800',
            'resolved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getPriorityBadgeClass()
    {
        return match($this->priority) {
            'low' => 'bg-gray-100 text-gray-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
