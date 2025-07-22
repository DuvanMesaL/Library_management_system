<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of books
     */
    public function index(Request $request)
    {
        $query = Book::with('category');

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply category filter
        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        // Apply availability filter
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->available();
            } elseif ($request->availability === 'unavailable') {
                $query->where(function ($q) {
                    $q->where('is_active', false)
                      ->orWhere('copies_available', 0);
                });
            }
        }

        $books = $query->orderBy('title')->paginate(12);
        $categories = Category::orderBy('name')->get();

        // Get statistics
        $stats = [
            'total' => Book::count(),
            'available' => Book::available()->count(),
            'unavailable' => Book::where('copies_available', 0)->count(),
            'categories' => Category::count(),
        ];

        return view('books.index', compact('books', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new book
     */
    public function create()
    {
        $this->authorize('manage-books');

        $categories = Category::orderBy('name')->get();

        return view('books.create', compact('categories'));
    }

    /**
     * Store a newly created book
     */
    public function store(Request $request)
    {
        $this->authorize('manage-books');

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'publication_year' => 'nullable|integer|min:1|max:' . date('Y'),
            'publisher' => 'nullable|string|max:255',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'copies_total' => 'required|integer|min:1|max:100',
            'location' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $bookData = $request->except('cover_image');
        $bookData['copies_available'] = $bookData['copies_total'];
        $bookData['is_active'] = true;

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('book-covers', 'public');
            $bookData['cover_image'] = $path;
        }

        $book = Book::create($bookData);

        return redirect()->route('books.show', $book)
            ->with('success', 'Libro creado exitosamente.');
    }

    /**
     * Display the specified book
     */
    public function show(Book $book)
    {
        $book->load('category');

        // Get recent loans for this book
        $recentLoans = $book->loans()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('books.show', compact('book', 'recentLoans'));
    }

    /**
     * Show the form for editing the specified book
     */
    public function edit(Book $book)
    {
        $this->authorize('manage-books');

        $categories = Category::orderBy('name')->get();

        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book
     */
    public function update(Request $request, Book $book)
    {
        $this->authorize('manage-books');

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'publication_year' => 'nullable|integer|min:1|max:' . date('Y'),
            'publisher' => 'nullable|string|max:255',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'copies_total' => 'required|integer|min:1|max:100',
            'copies_available' => 'required|integer|min:0|max:' . $request->copies_total,
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $bookData = $request->except('cover_image');

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $path = $request->file('cover_image')->store('book-covers', 'public');
            $bookData['cover_image'] = $path;
        }

        $book->update($bookData);

        return redirect()->route('books.show', $book)
            ->with('success', 'Libro actualizado exitosamente.');
    }

    /**
     * Remove the specified book
     */
    public function destroy(Book $book)
    {
        $this->authorize('manage-books');

        // Check if book has active loans
        if ($book->activeLoans()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un libro con préstamos activos.');
        }

        // Delete cover image
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Libro eliminado exitosamente.');
    }

    /**
     * Show loan form for a specific book
     */
    public function loan(Book $book)
    {
        if (!$book->isAvailable()) {
            return back()->with('error', 'Este libro no está disponible para préstamo.');
        }

        return view('books.loan', compact('book'));
    }

    /**
     * Process a loan request for a specific book
     */
    public function processLoan(Request $request, Book $book)
    {
        Log::info('Entrando a processLoan', [
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'request_all' => $request->all()
        ]);

        Log::info('Datos recibidos para validación', $request->all());

        // Validate the request
        $request->validate([
            'loan_days' => 'required|integer|in:7,14,21,30',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if book is available
        if (!$book->isAvailable()) {
            return back()->with('error', 'El libro no está disponible para préstamo.');
        }

        // Check user loan limit (optional business rule)
        $activeLoanCount = Loan::where('user_id', Auth::id())
                            ->where('status', 'active')
                            ->count();

        if ($activeLoanCount >= 5) {
            return back()->with('error', 'Has alcanzado el límite máximo de 5 préstamos activos.');
        }

        try {
            // Cast loan_days to integer to avoid Carbon type error
            $loanDays = (int) $request->loan_days;

            DB::transaction(function () use ($request, $book, $loanDays) {
                // Create the loan
                $loan = Loan::create([
                    'user_id' => Auth::id(),
                    'book_id' => $book->id,
                    'loan_date' => now()->toDateString(),
                    'due_date' => now()->addDays($loanDays)->toDateString(),
                    'status' => 'active',
                    'notes' => $request->notes,
                ]);

                // Decrease book availability
                $book->decreaseAvailability();

                Log::info('Préstamo creado exitosamente', [
                    'loan_id' => $loan->id,
                    'due_date' => $loan->due_date
                ]);
            });

            $dueDate = now()->addDays($loanDays)->format('d/m/Y');

            return redirect()->route('loans.index')
                ->with('success', "Préstamo creado exitosamente. Fecha de devolución: {$dueDate}");

        } catch (\Exception $e) {
            Log::error('Error al crear préstamo', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'book_id' => $book->id
            ]);

            return back()->with('error', 'Error al procesar el préstamo. Por favor, inténtalo de nuevo.');
        }
    }
}
