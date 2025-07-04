<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitation;
use App\Jobs\SendInvitationEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('active');
    }

    /**
     * Display a listing of invitations
     */
    public function index()
    {
        if (!Gate::allows('invite-users')) {
            abort(403, 'No tienes permisos para ver las invitaciones.');
        }

        $invitations = Invitation::with(['role', 'invitedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.invitations.index', compact('invitations'));
    }

    /**
     * Show the form for creating a new invitation
     */
    public function create()
    {
        if (!Gate::allows('invite-users')) {
            abort(403, 'No tienes permisos para crear invitaciones.');
        }

        $roles = Role::where('name', '!=', 'admin')->get();

        return view('admin.invitations.create', compact('roles'));
    }

    /**
     * Store a newly created invitation
     */
    public function store(Request $request)
    {
        if (!Gate::allows('invite-users')) {
            abort(403, 'No tienes permisos para crear invitaciones.');
        }

        $request->validate([
            'email' => 'required|email|unique:users,email|unique:invitations,email',
            'role_id' => 'required|exists:roles,id',
            'message' => 'nullable|string|max:500',
        ]);

        $invitation = Invitation::create([
            'email' => $request->email,
            'role_id' => $request->role_id,
            'token' => Str::random(32),
            'invited_by' => Auth::id(),
            'message' => $request->message,
            'expires_at' => now()->addDays(7),
        ]);

        // Send invitation email
        SendInvitationEmail::dispatch($invitation);

        return redirect()->route('admin.invitations.index')
            ->with('success', 'Invitación enviada exitosamente.');
    }

    /**
     * Remove the specified invitation
     */
    public function destroy(Invitation $invitation)
    {
        if (!Gate::allows('invite-users')) {
            abort(403, 'No tienes permisos para eliminar invitaciones.');
        }

        $invitation->delete();

        return back()->with('success', 'Invitación eliminada exitosamente.');
    }
}
