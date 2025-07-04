@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg mb-8 overflow-hidden">
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
                        <p class="text-purple-100 mt-2">Tu Biblioteca Personal - Descubre y Lee</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Active Loans -->
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11m-6 0h6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Mis Préstamos Activos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $active_loans }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Read -->
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Libros Leídos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $my_loans->where('status', 'returned')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Recent Loans -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 mb-8">
            <div class="px-6 py-4 border-b border-amber-200">
                <h3 class="text-lg font-semibold text-amber-900">Mis Préstamos Recientes</h3>
            </div>
            <div class="p-6">
                @if($my_loans->count() > 0)
                    <div class="space-y-4">
                        @foreach($my_loans as $loan)
                            <div class="flex items-center justify-between p-4 bg-amber-50 rounded-lg border border-amber-100">
                                <div class="flex items-center space-x-4">
                                    <div class="h-12 w-12 bg-amber-200 rounded-lg flex items-center justify-center">
                                        <svg class="h-6 w-6 text-amber-700" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $loan->book->title }}</p>
                                        <p class="text-sm text-gray-600">{{ $loan->book->author }}</p>
                                        <p class="text-sm text-gray-500">{{ $loan->book->category->name ?? 'Sin categoría' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $loan->loan_date->format('d/m/Y') }}</p>
                                    @if($loan->status === 'active')
                                        <p class="text-sm text-gray-600">Vence: {{ $loan->due_date->format('d/m/Y') }}</p>
                                    @else
                                        <p class="text-sm text-gray-600">Devuelto: {{ $loan->return_date->format('d/m/Y') }}</p>
                                    @endif
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($loan->status === 'active') bg-blue-100 text-blue-800
                                        @elseif($loan->status === 'returned') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if($loan->status === 'active') Activo
                                        @elseif($loan->status === 'returned') Devuelto
                                        @else Vencido @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"/>
                        </svg>
                        <p class="mt-2 text-sm text-amber-600">Aún no has solicitado ningún préstamo</p>
                        <p class="text-sm text-amber-500">¡Explora nuestro catálogo y encuentra tu próxima lectura!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Books -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200">
            <div class="px-6 py-4 border-b border-amber-200">
                <h3 class="text-lg font-semibold text-amber-900">Libros Recientes en el Catálogo</h3>
            </div>
            <div class="p-6">
                @if($recent_books->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($recent_books as $book)
                            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg border border-amber-200 p-4 hover:shadow-lg transition duration-300">
                                <div class="flex items-start space-x-4">
                                    <div class="h-16 w-12 bg-amber-300 rounded flex items-center justify-center flex-shrink-0">
                                        <svg class="h-8 w-8 text-amber-700" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 text-sm leading-tight">{{ $book->title }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $book->author }}</p>
                                        <p class="text-xs text-amber-600 mt-1">{{ $book->category->name ?? 'Sin categoría' }}</p>
                                        <div class="flex items-center justify-between mt-3">
                                            <span class="text-xs text-gray-500">
                                                {{ $book->copies_available }}/{{ $book->copies_total }} disponibles
                                            </span>
                                            @if($book->isAvailable())
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Disponible
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    No disponible
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"/>
                        </svg>
                        <p class="mt-2 text-sm text-amber-600">No hay libros disponibles</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="#"
               class="group bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 hover:shadow-xl transition duration-300 transform hover:scale-105">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition duration-300">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-gray-900">Explorar Catálogo</p>
                        <p class="text-sm text-gray-600">Buscar libros disponibles</p>
                    </div>
                </div>
            </a>

            <a href="#"
               class="group bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 hover:shadow-xl transition duration-300 transform hover:scale-105">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition duration-300">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-gray-900">Mi Historial</p>
                        <p class="text-sm text-gray-600">Ver todos mis préstamos</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
