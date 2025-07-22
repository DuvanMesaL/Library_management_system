@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-amber-100 rounded-full flex items-center justify-center">
                <svg class="h-10 w-10 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-bold text-amber-900 font-serif">Biblioteca Digital</h2>
            <p class="mt-2 text-sm text-amber-700">Ingresa a tu cuenta para continuar</p>
        </div>

        <!-- Login Form -->
        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="rounded-lg bg-white/70 backdrop-blur-sm shadow-xl p-8 border border-amber-200">
                <div class="space-y-6">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-amber-800 mb-2">
                            Correo Electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   class="form-input input-focus"
                                   placeholder="tu@email.com" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-amber-800 mb-2">
                            Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   class="form-input input-focus"
                                   placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox"
                                   class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-amber-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-amber-700">
                                Recordarme
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-amber-600 hover:text-amber-500 transition duration-150 ease-in-out">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="btn-primary w-full">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-amber-300 group-hover:text-amber-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Iniciar Sesión
                        </button>
                    </div>

                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <div class="text-center">
                            <p class="text-sm text-amber-600">
                                ¿No tienes cuenta?
                                <a href="{{ route('register') }}" class="font-medium text-amber-700 hover:text-amber-600 transition duration-150 ease-in-out">
                                    Regístrate aquí
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </form>

        <!-- Demo Users Info -->
        <div class="mt-8 bg-white/50 backdrop-blur-sm rounded-lg p-4 border border-amber-200">
            <h3 class="text-sm font-medium text-amber-800 mb-2">Usuarios de Prueba:</h3>
            <div class="text-xs text-amber-700 space-y-1">
                <p><strong>Admin:</strong> admin@biblioteca.com (password)</p>
                <p><strong>Bibliotecario:</strong> bibliotecario@biblioteca.com (password)</p>
                <p><strong>Lector:</strong> lector@biblioteca.com (password)</p>
            </div>
        </div>
    </div>
</div>
@endsection
