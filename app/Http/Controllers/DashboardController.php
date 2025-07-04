<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isBibliotecario()) {
            return $this->bibliotecarioDashboard();
        } else {
            return $this->lectorDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'active_loans' => Loan::where('status', 'active')->count(),
            'overdue_loans' => Loan::where('status', 'active')
                ->where('due_date', '<', now())
                ->count(),
        ];

        $recent_loans = Loan::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'recent_loans'));
    }

    private function bibliotecarioDashboard()
    {
        $stats = [
            'total_books' => Book::count(),
            'active_loans' => Loan::where('status', 'active')->count(),
            'overdue_loans' => Loan::where('status', 'active')
                ->where('due_date', '<', now())
                ->count(),
            'books_available' => Book::where('copies_available', '>', 0)->count(),
        ];

        $recent_loans = Loan::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.bibliotecario', compact('stats', 'recent_loans'));
    }

    private function lectorDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $my_loans = $user->loans()
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $active_loans = $user->activeLoans()->count();

        $recent_books = Book::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('dashboard.lector', compact('my_loans', 'active_loans', 'recent_books'));
    }
}
