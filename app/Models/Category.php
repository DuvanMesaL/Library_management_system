<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function activeBooks()
    {
        return $this->hasMany(Book::class)->where('is_active', true);
    }

    public function getBooksCount()
    {
        return $this->books()->count();
    }

    public function getActiveBooksCount()
    {
        return $this->activeBooks()->count();
    }

    public function getAvailableBooksCount()
    {
        return $this->activeBooks()->where('copies_available', '>', 0)->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getColorClass()
    {
        $colors = [
            'blue' => 'bg-blue-100 text-blue-800',
            'green' => 'bg-green-100 text-green-800',
            'yellow' => 'bg-yellow-100 text-yellow-800',
            'red' => 'bg-red-100 text-red-800',
            'purple' => 'bg-purple-100 text-purple-800',
            'pink' => 'bg-pink-100 text-pink-800',
            'indigo' => 'bg-indigo-100 text-indigo-800',
            'gray' => 'bg-gray-100 text-gray-800',
        ];

        return $colors[$this->color] ?? $colors['gray'];
    }
}
