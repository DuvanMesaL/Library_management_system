<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'category_id',
        'description',
        'publication_year',
        'copies_total',
        'copies_available',
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'copies_total' => 'integer',
            'copies_available' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function activeLoans()
    {
        return $this->hasMany(Loan::class)->where('status', 'active');
    }

    public function isAvailable()
    {
        return $this->copies_available > 0;
    }

    public function decreaseAvailability()
    {
        if ($this->copies_available > 0) {
            $this->decrement('copies_available');
        }
    }

    public function increaseAvailability()
    {
        if ($this->copies_available < $this->copies_total) {
            $this->increment('copies_available');
        }
    }
}
