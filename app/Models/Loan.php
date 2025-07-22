<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isReturned()
    {
        return $this->status === 'returned';
    }

    public function isOverdue()
    {
        return $this->isActive() && $this->due_date < Carbon::today();
    }

    public function getDaysOverdue()
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return Carbon::today()->diffInDays($this->due_date);
    }

    public function getDaysRemaining()
    {
        if (!$this->isActive()) {
            return 0;
        }
        return $this->due_date->diffInDays(Carbon::today());
    }

    public function markAsReturned()
    {
        $this->update([
            'status' => 'returned',
            'return_date' => Carbon::today(),
        ]);

        // Increase book availability
        $this->book->increaseAvailability();
    }

    public function extendDueDate($days)
    {
        $this->update([
            'due_date' => $this->due_date->addDays($days),
        ]);
    }

    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case 'active':
                return $this->isOverdue() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
            case 'returned':
                return 'bg-gray-100 text-gray-800';
            case 'overdue':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    public function getStatusText()
    {
        switch ($this->status) {
            case 'active':
                return $this->isOverdue() ? 'Vencido' : 'Activo';
            case 'returned':
                return 'Devuelto';
            case 'overdue':
                return 'Vencido';
            default:
                return 'Desconocido';
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
                    ->where('due_date', '<', Carbon::today());
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }


    public function canBeClaimed()
    {
        return $this->isActive() && !$this->claims()->whereIn('status', ['pending', 'in_review'])->exists();
    }
}
