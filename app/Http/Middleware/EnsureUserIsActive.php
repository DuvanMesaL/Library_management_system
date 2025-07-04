<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('🛡️ Entrando al middleware EnsureUserIsActive');

        if (!Auth::check()) {
            Log::warning('⚠️ Usuario no autenticado.');
            return redirect()->route('login');
        }

        $user = Auth::user();
        Log::info('👤 Usuario autenticado:', ['email' => $user->email, 'is_active' => $user->is_active]);

        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::error('🚫 Usuario inactivo fue desconectado.');
            return redirect()->route('login')->with('error', 'Tu cuenta ha sido desactivada.');
        }

        return $next($request);
    }
}
