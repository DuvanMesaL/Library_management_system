<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitation;
use App\Jobs\SendInvitationEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

            if (!$user->canInviteUsers()) {
                abort(403, 'No tienes permisos para invitar usuarios.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $invitations = Invitation::with(['role', 'invitedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.invitations.index', compact('invitations'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.invitations.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email|unique:invitations,email',
            'role_id' => 'required|exists:roles,id',
        ]);

        $invitation = Invitation::create([
            'email' => $request->email,
            'role_id' => $request->role_id,
            'invited_by' => Auth::id(),
            'token' => Invitation::generateToken(),
            'expires_at' => now()->addDays(7), // Expira en 7 días
        ]);

        // Dispatch the email job to the queue
        try {
            if (env('QUEUE_CONNECTION') === 'database') {
                SendInvitationEmail::dispatch($invitation);
                $message = 'Invitación creada y enviada a la cola de correos.';
            } else {
                // Send immediately if not using queue
                Mail::to($invitation->email)->send(new UserInvitation($invitation));
                $message = 'Invitación enviada correctamente.';
            }

            return redirect()->route('admin.invitations.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error processing invitation', [
                'invitation_id' => $invitation->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.invitations.index')
                ->with('error', 'Error al procesar la invitación: ' . $e->getMessage());
        }
    }

    public function destroy(Invitation $invitation)
    {
        $invitation->delete();

        return redirect()->route('admin.invitations.index')
            ->with('success', 'Invitación eliminada correctamente.');
    }
}
