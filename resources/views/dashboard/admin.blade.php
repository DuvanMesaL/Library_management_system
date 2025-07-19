@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-lg shadow-lg p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">¡Bienvenido, Administrador!</h1>
                        <p class="text-amber-100">Gestiona todo el sistema de biblioteca desde aquí</p>
                    </div>
                    <div class="hidden md:block">
                        <svg class="w-16 h-16 text-amber-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Libros</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_books'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Préstamos Activos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active_loans'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM9 7a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 000-2H9z"/>
                            <path d="M7 14a5.971 5.971 0 00-.75-2.906A3.005 3.005 0 0119 15v2a2 2 0 01-2 2H3a2 2 0 01-2-2v-2a3 3 0 013.25-2.98A5.972 5.972 0 007 14zM15 7a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Usuarios</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Reclamos Pendientes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_claims'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Book Management -->
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                    </svg>
                    Gestión de Libros
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('books.create') }}"
                       class="w-full flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 rounded-lg border border-green-200 transition duration-300 group">
                        <span class="text-green-800 font-medium">Agregar Nuevo Libro</span>
                        <svg class="w-5 h-5 text-green-600 group-hover:translate-x-1 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </a>
                    <a href="{{ route('books.index') }}"
                       class="w-full flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 rounded-lg border border-blue-200 transition duration-300 group">
                        <span class="text-blue-800 font-medium">Ver Catálogo</span>
                        <svg class="w-5 h-5 text-blue-600 group-hover:translate-x-1 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Loan Management -->
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Gestión de Préstamos
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('loans.create') }}"
                       class="w-full flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 rounded-lg border border-purple-200 transition duration-300 group">
                        <span class="text-purple-800 font-medium">Crear Préstamo</span>
                        <svg class="w-5 h-5 text-purple-600 group-hover:translate-x-1 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </a>
                    <a href="{{ route('loans.index') }}"
                       class="w-full flex items-center justify-between p-3 bg-gradient-to-r from-orange-50 to-red-50 hover:from-orange-100 hover:to-red-100 rounded-lg border border-orange-200 transition duration-300 group">
                        <span class="text-orange-800 font-medium">Ver Préstamos</span>
                        <svg class="w-5 h-5 text-orange-600 group-hover:translate-x-1 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- User Management -->
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM9 7a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 000-2H9z"/>
                        <path d="M7 14a5.971 5.971 0 00-.75-2.906A3.005 3.005 0 0119 15v2a2 2 0 01-2 2H3a2 2 0 01-2-2v-2a3 3 0 013.25-2.98A5.972 5.972 0 007 14zM15 7a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Gestión de Usuarios
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.invitations.create') }}"
                       class="w-full flex items-center justify-between p-3 bg-gradient-to-r from-teal-50 to-cyan-50 hover:from-teal-100 hover:to-cyan-100 rounded-lg border border-teal-200 transition duration-300 group">
                        <span class="text-teal-800 font-medium">Invitar Usuario</span>
                        <svg class="w-5 h-5 text-teal-600 group-hover:translate-x-1 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </a>
                    <a href="{{ route('admin.invitations.index') }}"
                       class="w-full flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-slate-50 hover:from-gray-100 hover:to-slate-100 rounded-lg border border-gray-200 transition duration-300 group">
                        <span class="text-gray-800 font-medium">Ver Invitaciones</span>
                        <svg class="w-5 h-5 text-gray-600 group-hover:translate-x-1 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Claims Management -->
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Gestión de Reclamos
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('claims.create') }}"
                       class="w-full flex items-center justify-between p-3 bg-gradient-to-r from-yellow-50 to-amber-50 hover:from-yellow-100 hover:to-amber-100 rounded-lg border border-yellow-200 transition duration-300 group">
                        <span class="text-yellow-800 font-medium">Crear Reclamo</span>
                        <svg class="w-5 h-5 text-yellow-600 group-hover:translate-x-1 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </a>
                    <a href="{{ route('claims.index') }}"
                       class="w-full flex items-center justify-between p-3 bg-gradient-to-r from-red-50 to-pink-50 hover:from-red-100 hover:to-pink-100 rounded-lg border border-red-200 transition duration-300 group">
                        <span class="text-red-800 font-medium">Ver Reclamos</span>
                        <svg class="w-5 h-5 text-red-600 group-hover:translate-x-1 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        @if(isset($recentLoans) && $recentLoans->count() > 0)
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                Actividad Reciente
            </h3>
            <div class="space-y-3">
                @foreach($recentLoans as $loan)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">{{ $loan->book->title }}</p>
                        <p class="text-sm text-gray-600">Prestado a {{ $loan->user->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $loan->created_at->diffForHumans() }}</p>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($loan->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
