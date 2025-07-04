<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Check rate limiting
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            $this->sendLockoutResponse($request);
        }

        // Attempt to log the user in
        if ($this->attemptLogin($request)) {
            RateLimiter::clear($this->throttleKey($request));

            return $this->sendLoginResponse($request);
        }

        // Increment rate limiter attempts
        RateLimiter::hit($this->throttleKey($request));

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);
    }

    /**
     * Attempt to log the user into the application
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        // Add active user check
        $credentials['is_active'] = true;

        return Auth::attempt(
            $credentials,
            $request->boolean('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request
     */
    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    /**
     * Send the response after the user was authenticated
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $user = Auth::user();

        // Log successful login
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role->name ?? 'no_role'
        ]);

        return redirect()->intended('/dashboard')
            ->with('success', "¡Bienvenido de vuelta, {$user->name}!");
    }

    /**
     * Get the failed login response instance
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => ['Las credenciales proporcionadas no coinciden con nuestros registros o tu cuenta está inactiva.'],
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request
     */
    protected function throttleKey(Request $request)
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }

    /**
     * Redirect the user after determining they are locked out
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => ["Demasiados intentos de inicio de sesión. Inténtalo de nuevo en {$seconds} segundos."],
        ]);
    }

    /**
     * Log the user out of the application
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log successful logout
        if ($user) {
            Log::info('User logged out successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
        }

        return redirect('/login')
            ->with('success', 'Has cerrado sesión correctamente.');
    }
}
