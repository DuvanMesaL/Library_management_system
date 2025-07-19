<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'publisher',
        'pages',
        'language',
        'copies_total',
        'copies_available',
        'cover_image',
        'location',
        'is_active',
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'pages' => 'integer',
        'copies_total' => 'integer',
        'copies_available' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relación con la categoría del libro
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación con los préstamos del libro
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Obtener préstamos activos del libro
     */
    public function activeLoans()
    {
        return $this->hasMany(Loan::class)->where('status', 'active');
    }

    /**
     * Verificar si el libro está disponible para préstamo
     */
    public function isAvailable(): bool
    {
        return $this->is_active && $this->copies_available > 0;
    }

    /**
     * Reducir copias disponibles (cuando se presta)
     */
    public function decreaseAvailability(): void
    {
        if ($this->copies_available > 0) {
            $this->decrement('copies_available');
        }
    }

    /**
     * Aumentar copias disponibles (cuando se devuelve)
     */
    public function increaseAvailability(): void
    {
        if ($this->copies_available < $this->copies_total) {
            $this->increment('copies_available');
        }
    }

    /**
     * Obtener la URL de la imagen de portada
     */
    public function getCoverImageUrl(): string
    {
        if ($this->cover_image && Storage::disk('public')->exists($this->cover_image)) {
            return Storage::url($this->cover_image);
        }

        // Generar URL de placeholder con el título del libro
        return '/placeholder.svg?height=300&width=200&text=' . urlencode($this->title);
    }

    /**
     * Obtener el estado de disponibilidad como texto
     */
    public function getAvailabilityStatus(): string
    {
        if (!$this->is_active) {
            return 'Inactivo';
        }

        if ($this->copies_available === 0) {
            return 'No disponible';
        }

        if ($this->copies_available === 1) {
            return '1 copia disponible';
        }

        return $this->copies_available . ' copias disponibles';
    }

    /**
     * Obtener la clase CSS para el badge de disponibilidad
     */
    public function getAvailabilityBadgeClass(): string
    {
        if (!$this->is_active) {
            return 'bg-gray-100 text-gray-800';
        }

        if ($this->copies_available === 0) {
            return 'bg-red-100 text-red-800';
        }

        if ($this->copies_available <= 2) {
            return 'bg-yellow-100 text-yellow-800';
        }

        return 'bg-green-100 text-green-800';
    }

    /**
     * Scope para libros disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
                    ->where('copies_available', '>', 0);
    }

    /**
     * Scope para filtrar por categoría
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope para buscar libros
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('author', 'like', '%' . $search . '%')
              ->orWhere('isbn', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    /**
     * Obtener el número de préstamos activos
     */
    public function getActiveLoansCount(): int
    {
        return $this->activeLoans()->count();
    }

    /**
     * Obtener el número total de préstamos
     */
    public function getTotalLoansCount(): int
    {
        return $this->loans()->count();
    }

    /**
     * Verificar si el libro puede ser prestado
     */
    public function canBeBorrowed(): bool
    {
        return $this->isAvailable();
    }

    /**
     * Obtener información completa de disponibilidad
     */
    public function getAvailabilityInfo(): array
    {
        return [
            'is_available' => $this->isAvailable(),
            'copies_available' => $this->copies_available,
            'copies_total' => $this->copies_total,
            'copies_on_loan' => $this->copies_total - $this->copies_available,
            'status_text' => $this->getAvailabilityStatus(),
            'badge_class' => $this->getAvailabilityBadgeClass(),
        ];
    }
}
