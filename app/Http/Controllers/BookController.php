<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureUserIsActive;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(EnsureUserIsActive::class);
    }

    /**
     * Display a listing of books
     */
    public function index(Request $request)
    {
        $query = Book::with(['category']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('copies_available', '>', 0);
            } elseif ($request->availability === 'unavailable') {
                $query->where('copies_available', 0);
            }
        }

        $books = $query->orderBy('title')->paginate(12);
        $categories = Category::orderBy('name')->get();

        // Get statistics
        $stats = [
            'total' => Book::count(),
            'available' => Book::where('copies_available', '>', 0)->count(),
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
            'isbn' => 'nullable|string|max:20|unique:books,isbn',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'copies_total' => 'required|integer|min:1|max:100',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $bookData = $request->only([
            'title', 'author', 'isbn', 'category_id', 'description',
            'publication_year', 'copies_total'
        ]);

        $bookData['copies_available'] = $request->copies_total;

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $bookData['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        Book::create($bookData);

        return redirect()->route('books.index')
            ->with('success', 'Libro creado exitosamente.');
    }

    /**
     * Display the specified book
     */
    public function show(Book $book)
    {
        $book->load(['category', 'loans.user', 'activeLoans.user']);

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
            'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'copies_total' => 'required|integer|min:1|max:100',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $bookData = $request->only([
            'title', 'author', 'isbn', 'category_id', 'description',
            'publication_year', 'copies_total'
        ]);

        // Adjust available copies if total changed
        $currentLoaned = $book->copies_total - $book->copies_available;
        $bookData['copies_available'] = max(0, $request->copies_total - $currentLoaned);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $bookData['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        $book->update($bookData);

        return redirect()->route('books.show', $book)
            ->with('success', 'Libro actualizado exitosamente.');
    }

    /**
     * Remove the specified book from storage
     */
    public function destroy(Book $book)
    {
        $this->authorize('manage-books');

        // Check if book has active loans
        if ($book->activeLoans()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un libro con préstamos activos.');
        }

        // Delete cover image if exists
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
     * Process book loan request
     */
    public function processLoan(Request $request, Book $book)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'loan_days' => 'required|integer|min:1|max:30',
            'notes' => 'nullable|string|max:500',
        ]);

        if (!$book->isAvailable()) {
            return back()->with('error', 'Este libro no está disponible para préstamo.');
        }

        // Check if user already has this book on loan
        $existingLoan = $user->activeLoans()
            ->where('book_id', $book->id)
            ->exists();

        if ($existingLoan) {
            return back()->with('error', 'Ya tienes este libro en préstamo.');
        }

        // Cast to int para evitar error de tipo en Carbon
        $loanDays = (int) $request->input('loan_days');

        DB::transaction(function () use ($request, $book, $loanDays) {
            // Create loan
            \App\Models\Loan::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'loan_date' => \Carbon\Carbon::today(),
                'due_date' => \Carbon\Carbon::today()->addDays($loanDays),
                'status' => 'active',
                'notes' => $request->notes,
            ]);

            // Decrease book availability
            $book->decreaseAvailability();
        });

        return redirect()->route('loans.index')
            ->with('success', 'Préstamo solicitado exitosamente.');
    }
}
