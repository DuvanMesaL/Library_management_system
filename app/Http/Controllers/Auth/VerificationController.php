<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the email verification notice
     */
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->hasVerifiedEmail()
            ? redirect()->route('dashboard')
            : view('auth.verify');
    }

    /**
     * Mark the authenticated user's email address as verified
     */
    public function verify(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            throw new \Illuminate\Auth\Access\AuthorizationException;
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new \Illuminate\Auth\Access\AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified(Auth::user()));
        }

        return redirect()->route('dashboard')
            ->with('success', '¡Tu email ha sido verificado exitosamente!');
    }

    /**
     * Resend the email verification notification
     */
    public function resend(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Se ha enviado un nuevo enlace de verificación a tu email.');
    }
}
