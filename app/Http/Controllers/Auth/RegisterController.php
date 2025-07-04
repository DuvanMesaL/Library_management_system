<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeUser;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm(Request $request)
    {
        $token = $request->get('token');
        $invitation = null;

        if ($token) {
            $invitation = Invitation::where('token', $token)
                ->where('status', 'pending')
                ->first();

            if (!$invitation || $invitation->isExpired()) {
                return redirect()->route('login')
                    ->with('error', 'El enlace de invitación es inválido o ha expirado.');
            }
        }

        return view('auth.register', compact('invitation'));
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        // Log the user in
        Auth::login($user);

        return redirect()->intended('/dashboard')
            ->with('success', '¡Bienvenido! Tu cuenta ha sido creada exitosamente.');
    }

    /**
     * Get a validator for an incoming registration request
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];

        // Validate invitation token if present
        if (isset($data['invitation_token'])) {
            $rules['invitation_token'] = ['string', 'exists:invitations,token'];
            $messages['invitation_token.exists'] = 'El token de invitación no es válido.';
        }

        return Validator::make($data, $rules, $messages);
    }

    /**
     * Create a new user instance after a valid registration
     */
    protected function create(array $data)
    {
        // Create the user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
            'email_verified_at' => now(), // Auto-verify for now
        ]);

        // Handle invitation if present
        if (isset($data['invitation_token']) && !empty($data['invitation_token'])) {
            $this->processInvitation($user, $data['invitation_token']);
        } else {
            // Assign default reader role if no invitation
            $this->assignDefaultRole($user);
        }

        // Send welcome email
        $this->sendWelcomeEmail($user);

        return $user;
    }

    /**
     * Process invitation and assign role
     */
    protected function processInvitation(User $user, string $token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('status', 'pending')
            ->first();

        if ($invitation && !$invitation->isExpired()) {
            $user->update([
                'role_id' => $invitation->role_id,
                'invited_by' => $invitation->invited_by,
                'invitation_accepted_at' => now(),
            ]);

            $invitation->markAsAccepted();

            Log::info('User registered via invitation', [
                'user_id' => $user->id,
                'invitation_id' => $invitation->id,
                'role' => $invitation->role->name
            ]);
        }
    }

    /**
     * Assign default reader role
     */
    protected function assignDefaultRole(User $user)
    {
        $lectorRole = Role::where('name', 'lector')->first();

        if ($lectorRole) {
            $user->update(['role_id' => $lectorRole->id]);
        }

        Log::info('User registered with default role', [
            'user_id' => $user->id,
            'role' => 'lector'
        ]);
    }

    /**
     * Send welcome email to new user
     */
    protected function sendWelcomeEmail(User $user)
    {
        try {
            if (env('WELCOME_EMAIL_ENABLED', true)) {
                if (env('QUEUE_CONNECTION') === 'database') {
                    SendWelcomeEmail::dispatch($user);
                } else {
                    Mail::to($user->email)->send(new WelcomeUser($user));
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
