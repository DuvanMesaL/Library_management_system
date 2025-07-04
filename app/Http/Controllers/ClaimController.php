<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureUserIsActive;

class ClaimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(EnsureUserIsActive::class);
    }

    /**
     * Display a listing of claims
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Claim::with(['loan.book', 'user', 'assignedTo']);

        // Filter by user role
        if ($user->isLector()) {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $claims = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = $this->getClaimStatistics($user);

        return view('claims.index', compact('claims', 'stats'));
    }

    /**
     * Show the form for creating a new claim
     */
    public function create(Request $request)
    {
        $loanId = $request->get('loan_id');
        $loan = null;

        if ($loanId) {
            $loan = Loan::with(['book', 'user'])
                       ->where('id', $loanId)
                       ->where('user_id', Auth::id())
                       ->first();

            if (!$loan || !$loan->canBeClaimed()) {
                return redirect()->route('loans.index')
                    ->with('error', 'No puedes crear un reclamo para este préstamo.');
            }
        }

        // Get user's active loans that can be claimed
        $availableLoans = Loan::with('book')
                             ->where('user_id', Auth::id())
                             ->where('status', 'active')
                             ->whereDoesntHave('claims', function($q) {
                                 $q->where('status', 'pending');
                             })
                             ->get();

        return view('claims.create', compact('loan', 'availableLoans'));
    }

    /**
     * Store a newly created claim
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'priority' => 'required|in:low,medium,high',
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        // Verify ownership and eligibility
        if ($loan->user_id !== Auth::id() || !$loan->canBeClaimed()) {
            return back()->with('error', 'No puedes crear un reclamo para este préstamo.');
        }

        Claim::create([
            'loan_id' => $request->loan_id,
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);

        return redirect()->route('claims.index')
            ->with('success', 'Reclamo enviado exitosamente. Será revisado por el personal de la biblioteca.');
    }

    /**
     * Display the specified claim
     */
    public function show(Claim $claim)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check permissions
        if ($user->isLector() && $claim->user_id !== $user->id) {
            abort(403, 'No tienes permisos para ver este reclamo.');
        }

        $claim->load(['loan.book', 'user', 'assignedTo']);

        return view('claims.show', compact('claim'));
    }

    /**
     * Update claim status (for admins/librarians)
     */
    public function update(Request $request, Claim $claim)
    {
        $this->authorize('manage-loans');

        $request->validate([
            'status' => 'required|in:pending,in_review,resolved,rejected',
            'admin_response' => 'nullable|string|max:1000',
        ]);

        $updateData = [
            'status' => $request->status,
            'assigned_to' => Auth::id(),
        ];

        if ($request->filled('admin_response')) {
            $updateData['admin_response'] = $request->admin_response;
        }

        if ($request->status === 'resolved') {
            $updateData['resolved_at'] = now();
        }

        $claim->update($updateData);

        return back()->with('success', 'Reclamo actualizado exitosamente.');
    }

    /**
     * Get claim statistics
     */
    private function getClaimStatistics($user)
    {
        $query = Claim::query();

        if ($user->isLector()) {
            $query->where('user_id', $user->id);
        }

        return [
            'total' => $query->count(),
            'pending' => $query->where('status', 'pending')->count(),
            'in_review' => $query->where('status', 'in_review')->count(),
            'resolved' => $query->where('status', 'resolved')->count(),
        ];
    }
}
