@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-lg shadow-lg mb-8 overflow-hidden">
            <div class="px-6 py-8 sm:px-8">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h1 class="text-3xl font-bold text-white font-serif">
                            Bienvenido, {{ Auth::user()->name }}
                        </h1>
                        <p class="text-amber-100 mt-2">Panel de Administración - Biblioteca Digital</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Usuarios</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Books -->
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Libros</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_books'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Loans -->
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11m-6 0h6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Préstamos Activos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active_loans'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Overdue Loans -->
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Préstamos Vencidos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['overdue_loans'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200">
            <div class="px-6 py-4 border-b border-amber-200">
                <h3 class="text-lg font-semibold text-amber-900">Préstamos Recientes</h3>
            </div>
            <div class="p-6">
                @if($recent_loans->count() > 0)
                    <div class="space-y-4">
                        @foreach($recent_loans as $loan)
                            <div class="flex items-center justify-between p-4 bg-amber-50 rounded-lg border border-amber-100">
                                <div class="flex items-center space-x-4">
                                    <div class="h-10 w-10 bg-amber-200 rounded-full flex items-center justify-center">
                                        <svg class="h-5 w-5 text-amber-700" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $loan->book->title }}</p>
                                        <p class="text-sm text-gray-600">Prestado a: {{ $loan->user->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $loan->loan_date->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-600">Vence: {{ $loan->due_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-amber-600">No hay préstamos recientes</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.invitations.create') }}"
               class="group bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 hover:shadow-xl transition duration-300 transform hover:scale-105">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition duration-300">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-gray-900">Invitar Usuario</p>
                        <p class="text-sm text-gray-600">Enviar invitación por email</p>
                    </div>
                </div>
            </a>

            <a href="#"
               class="group bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 hover:shadow-xl transition duration-300 transform hover:scale-105">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition duration-300">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-gray-900">Gestionar Libros</p>
                        <p class="text-sm text-gray-600">Agregar y editar libros</p>
                    </div>
                </div>
            </a>

            <a href="#"
               class="group bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 hover:shadow-xl transition duration-300 transform hover:scale-105">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition duration-300">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-gray-900">Ver Reportes</p>
                        <p class="text-sm text-gray-600">Estadísticas detalladas</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
