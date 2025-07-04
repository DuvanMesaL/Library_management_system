<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureUserIsActive;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(EnsureUserIsActive::class);
    }

    /**
     * Display a listing of loans
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Base query
        $query = Loan::with(['user', 'book.category']);

        // Filter by user role
        if ($user->isLector()) {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id') && $user->canManageLoans()) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('book_search')) {
            $query->whereHas('book', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->book_search . '%')
                  ->orWhere('author', 'like', '%' . $request->book_search . '%');
            });
        }

        // Order by most recent
        $loans = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get users for filter (only for admins/librarians)
        $users = $user->canManageLoans() ? User::orderBy('name')->get() : collect();

        // Get statistics
        $stats = $this->getLoanStatistics($user);

        return view('loans.index', compact('loans', 'users', 'stats'));
    }

    /**
     * Show the form for creating a new loan
     */
    public function create()
    {
        $this->authorize('manage-loans');

        $books = Book::where('copies_available', '>', 0)
                    ->with('category')
                    ->orderBy('title')
                    ->get();

        $users = User::where('is_active', true)
                    ->orderBy('name')
                    ->get();

        return view('loans.create', compact('books', 'users'));
    }

    /**
     * Store a newly created loan
     */
    public function store(Request $request)
    {
        $this->authorize('manage-loans');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_days' => 'required|integer|min:1|max:30',
            'notes' => 'nullable|string|max:500',
        ]);

        $book = Book::findOrFail($request->book_id);

        if (!$book->isAvailable()) {
            return back()->with('error', 'El libro no está disponible para préstamo.');
        }

        $loanDays = (int) $request->input('loan_days');

        DB::transaction(function () use ($request, $book, $loanDays) {
            Loan::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'loan_date' => Carbon::today(),
                'due_date' => Carbon::today()->addDays($loanDays),
                'status' => 'active',
                'notes' => $request->notes,
            ]);

            $book->decreaseAvailability();
        });

        return redirect()->route('loans.index')
            ->with('success', 'Préstamo creado exitosamente.');
    }

    /**
     * Display the specified loan
     */
    public function show(Loan $loan)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check permissions
        if ($user->isLector() && $loan->user_id !== $user->id) {
            abort(403, 'No tienes permisos para ver este préstamo.');
        }

        $loan->load(['user', 'book.category', 'claims.assignedTo']);

        return view('loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified loan
     */
    public function edit(Loan $loan)
    {
        $this->authorize('manage-loans');

        $books = Book::with('category')->orderBy('title')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('loans.edit', compact('loan', 'books', 'users'));
    }

    /**
     * Update the specified loan
     */
    public function update(Request $request, Loan $loan)
    {
        $this->authorize('manage-loans');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'due_date' => 'required|date|after_or_equal:loan_date',
            'status' => 'required|in:active,returned,overdue',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldBookId = $loan->book_id;
        $newBookId = $request->book_id;
        $oldStatus = $loan->status;
        $newStatus = $request->status;

        DB::transaction(function () use ($request, $loan, $oldBookId, $newBookId, $oldStatus, $newStatus) {
            // If book changed, update availability
            if ($oldBookId != $newBookId) {
                // Return old book
                $oldBook = Book::find($oldBookId);
                if ($oldBook && $oldStatus === 'active') {
                    $oldBook->increaseAvailability();
                }

                // Take new book
                $newBook = Book::find($newBookId);
                if ($newBook && $newStatus === 'active') {
                    if (!$newBook->isAvailable()) {
                        throw new \Exception('El libro seleccionado no está disponible.');
                    }
                    $newBook->decreaseAvailability();
                }
            }

            // If status changed from active to returned
            if ($oldStatus === 'active' && $newStatus === 'returned') {
                $book = Book::find($newBookId);
                if ($book) {
                    $book->increaseAvailability();
                }
                $loan->return_date = Carbon::today();
            }

            // If status changed from returned to active
            if ($oldStatus === 'returned' && $newStatus === 'active') {
                $book = Book::find($newBookId);
                if ($book) {
                    if (!$book->isAvailable()) {
                        throw new \Exception('El libro no está disponible para préstamo.');
                    }
                    $book->decreaseAvailability();
                }
                $loan->return_date = null;
            }

            // Update loan
            $loan->update([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'due_date' => $request->due_date,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);
        });

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Préstamo actualizado exitosamente.');
    }

    /**
     * Return a book (mark loan as returned)
     */
    public function return(Loan $loan)
    {
        $this->authorize('manage-loans');

        if ($loan->status !== 'active') {
            return back()->with('error', 'Este préstamo ya ha sido procesado.');
        }

        DB::transaction(function () use ($loan) {
            $loan->markAsReturned();
        });

        return back()->with('success', 'Libro devuelto exitosamente.');
    }

    /**
     * Extend loan due date
     */
    public function extend(Request $request, Loan $loan)
    {
        $this->authorize('manage-loans');

        $request->validate([
            'extend_days' => 'required|integer|min:1|max:15',
        ]);

        if ($loan->status !== 'active') {
            return back()->with('error', 'Solo se pueden extender préstamos activos.');
        }

        $extendDays = (int) $request->input('extend_days');

        $loan->update([
            'due_date' => $loan->due_date->addDays($extendDays),
        ]);

        return back()->with('success', "Préstamo extendido por {$extendDays} días.");
    }

    /**
     * Get loan statistics
     */
    private function getLoanStatistics($user)
    {
        $query = Loan::query();

        if ($user->isLector()) {
            $query->where('user_id', $user->id);
        }

        return [
            'total' => $query->count(),
            'active' => $query->where('status', 'active')->count(),
            'overdue' => $query->where('status', 'active')
                              ->where('due_date', '<', Carbon::today())
                              ->count(),
            'returned' => $query->where('status', 'returned')->count(),
        ];
    }
}
