@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-amber-100 rounded-full flex items-center justify-center">
                <svg class="h-10 w-10 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-bold text-amber-900 font-serif">
                @if(isset($invitation))
                    Completa tu Registro
                @else
                    Crear Cuenta
                @endif
            </h2>
            <p class="mt-2 text-sm text-amber-700">
                @if(isset($invitation))
                    Has sido invitado como <strong>{{ $invitation->role->name }}</strong>
                @else
                    Únete a nuestra biblioteca digital
                @endif
            </p>
        </div>

        <!-- Register Form -->
        <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
            @csrf

            @if(isset($invitation))
                <input type="hidden" name="invitation_token" value="{{ $invitation->token }}">
            @endif

            <div class="rounded-lg bg-white/70 backdrop-blur-sm shadow-xl p-8 border border-amber-200">
                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-amber-800 mb-2">
                            Nombre Completo
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input id="name" name="name" type="text" autocomplete="name" required
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-amber-300 rounded-lg leading-5 bg-white/50 placeholder-amber-500 focus:outline-none focus:placeholder-amber-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                   placeholder="Tu nombre completo" value="{{ old('name') }}">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

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
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-amber-300 rounded-lg leading-5 bg-white/50 placeholder-amber-500 focus:outline-none focus:placeholder-amber-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                   placeholder="tu@email.com"
                                   value="{{ isset($invitation) ? $invitation->email : old('email') }}"
                                   {{ isset($invitation) ? 'readonly' : '' }}>
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
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-amber-300 rounded-lg leading-5 bg-white/50 placeholder-amber-500 focus:outline-none focus:placeholder-amber-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                   placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-amber-800 mb-2">
                            Confirmar Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-amber-300 rounded-lg leading-5 bg-white/50 placeholder-amber-500 focus:outline-none focus:placeholder-amber-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="btn-primary hover-scale group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-amber-300 group-hover:text-amber-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                </svg>
                            </span>
                            Crear Cuenta
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-sm text-amber-600">
                            ¿Ya tienes cuenta?
                            <a href="{{ route('login') }}" class="font-medium text-amber-700 hover:text-amber-600 transition duration-150 ease-in-out">
                                Inicia sesión aquí
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
