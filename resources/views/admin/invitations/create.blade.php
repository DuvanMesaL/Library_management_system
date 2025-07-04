@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.invitations.index') }}"
                   class="text-amber-600 hover:text-amber-700 transition duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-amber-900 font-serif">Nueva Invitación</h1>
                    <p class="text-amber-700 mt-2">Invita a un nuevo usuario al sistema</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-8">
            <form method="POST" action="{{ route('admin.invitations.store') }}" class="space-y-6">
                @csrf

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
                        <input id="email" name="email" type="email" required
                               class="block w-full pl-10 pr-3 py-3 border border-amber-300 rounded-lg leading-5 bg-white/50 placeholder-amber-500 focus:outline-none focus:placeholder-amber-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-150 ease-in-out"
                               placeholder="usuario@email.com" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-amber-600">El usuario recibirá un email con el enlace de registro</p>
                </div>

                <!-- Role -->
                <div>
                    <label for="role_id" class="block text-sm font-medium text-amber-800 mb-2">
                        Rol del Usuario
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <select id="role_id" name="role_id" required
                                class="block w-full pl-10 pr-3 py-3 border border-amber-300 rounded-lg leading-5 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-150 ease-in-out">
                            <option value="">Selecciona un rol</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }} - {{ $role->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('role_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Descriptions -->
                <div class="bg-amber-50 rounded-lg p-4 border border-amber-200">
                    <h4 class="text-sm font-medium text-amber-800 mb-3">Descripción de Roles:</h4>
                    <div class="space-y-2 text-sm text-amber-700">
                        <div class="flex items-start space-x-2">
                            <div class="h-2 w-2 bg-red-400 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <strong>Administrador:</strong> Acceso completo al sistema, puede invitar usuarios y gestionar toda la plataforma.
                            </div>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="h-2 w-2 bg-blue-400 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <strong>Bibliotecario:</strong> Gestiona libros, préstamos y devoluciones. No puede invitar usuarios.
                            </div>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="h-2 w-2 bg-green-400 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <strong>Lector:</strong> Consulta catálogo, solicita préstamos y visualiza su historial personal.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route('admin.invitations.index') }}"
                       class="px-4 py-2 border border-amber-300 rounded-lg text-amber-700 hover:bg-amber-50 transition duration-150">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-medium rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        Enviar Invitación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
