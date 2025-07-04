@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-lg shadow-lg mb-8 overflow-hidden">
            <div class="px-6 py-8 sm:px-8">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h1 class="text-3xl font-bold text-white font-serif">
                            Bienvenido, {{ Auth::user()->name }}
                        </h1>
                        <p class="text-green-100 mt-2">Panel de Bibliotecario - Gestión de Colección</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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

            <!-- Available Books -->
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Libros Disponibles</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['books_available'] }}</p>
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

        <!-- Recent Loans -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 mb-8">
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
                                        <p class="text-sm text-gray-600">{{ $loan->book->author }}</p>
                                        <p class="text-sm text-gray-500">Prestado a: {{ $loan->user->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $loan->loan_date->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-600">Vence: {{ $loan->due_date->format('d/m/Y') }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($loan->status === 'active') bg-green-100 text-green-800
                                        @elseif($loan->status === 'overdue') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($loan->status) }}
                                    </span>
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="#"
               class="group bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 hover:shadow-xl transition duration-300 transform hover:scale-105">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition duration-300">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-gray-900">Agregar Libro</p>
                        <p class="text-sm text-gray-600">Nuevo libro a la colección</p>
                    </div>
                </div>
            </a>

            <a href="#"
               class="group bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 hover:shadow-xl transition duration-300 transform hover:scale-105">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition duration-300">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11m-6 0h6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-gray-900">Gestionar Préstamos</p>
                        <p class="text-sm text-gray-600">Ver y procesar préstamos</p>
                    </div>
                </div>
            </a>

            <a href="#"
               class="group bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 hover:shadow-xl transition duration-300 transform hover:scale-105">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition duration-300">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002-2V3a2 2 0 00-2-2H9a2 2 0 00-2 2v2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-gray-900">Inventario</p>
                        <p class="text-sm text-gray-600">Revisar estado de libros</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
