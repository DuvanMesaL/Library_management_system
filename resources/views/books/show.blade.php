@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header with Back Button -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('books.index') }}"
                   class="text-amber-600 hover:text-amber-700 transition duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-amber-900 font-serif">{{ $book->title }}</h1>
                    <p class="text-amber-700 mt-2">por {{ $book->author }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Book Cover and Basic Info -->
            <div class="lg:col-span-1">
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 sticky top-6">
                    <!-- Cover Image -->
                    <div class="mb-6">
                        <div class="aspect-[3/4] bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg flex items-center justify-center overflow-hidden">
                            @if($book->cover_image)
                                <img src="{{ $book->getCoverImageUrl() }}" alt="{{ $book->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <svg class="h-24 w-24 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Availability Status -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-medium text-gray-600">Disponibilidad:</span>
                            @if($book->isAvailable())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Disponible
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    No disponible
                                </span>
                            @endif
                        </div>
                        <div class="text-center">
                            <span class="text-2xl font-bold text-gray-900">{{ $book->copies_available }}</span>
                            <span class="text-gray-600"> de </span>
                            <span class="text-lg font-semibold text-gray-700">{{ $book->copies_total }}</span>
                            <p class="text-sm text-gray-500 mt-1">copias disponibles</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        @if($book->isAvailable() && auth()->user()->isLector())
                            <a href="{{ route('books.loan', $book) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Solicitar Préstamo
                            </a>
                        @endif

                        @can('manage-books')
                            <div class="flex space-x-2">
                                <a href="{{ route('books.edit', $book) }}"
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-sm font-medium rounded-lg shadow transition duration-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('books.destroy', $book) }}" class="flex-1"
                                      onsubmit="return confirm('¿Estás seguro de que quieres eliminar este libro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white text-sm font-medium rounded-lg shadow transition duration-300">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Book Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Libro</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Título</label>
                            <p class="text-gray-900 font-medium">{{ $book->title }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Autor</label>
                            <p class="text-gray-900">{{ $book->author }}</p>
                        </div>
                        @if($book->isbn)
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">ISBN</label>
                            <p class="text-gray-900 font-mono">{{ $book->isbn }}</p>
                        </div>
                        @endif
                        @if($book->category)
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Categoría</label>
                            <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 text-sm rounded-full">
                                {{ $book->category->name }}
                            </span>
                        </div>
                        @endif
                        @if($book->publication_year)
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Año de Publicación</label>
                            <p class="text-gray-900">{{ $book->publication_year }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Copias Totales</label>
                            <p class="text-gray-900">{{ $book->copies_total }}</p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($book->description)
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Descripción</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $book->description }}</p>
                </div>
                @endif

                <!-- Recent Loans -->
                @if($recentLoans->count() > 0)
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Préstamos Recientes</h2>
                    <div class="space-y-3">
                        @foreach($recentLoans as $loan)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $loan->user->name }}</p>
                                <p class="text-sm text-gray-600">
                                    Prestado: {{ $loan->loan_date->format('d/m/Y') }}
                                    @if($loan->due_date)
                                        - Vence: {{ $loan->due_date->format('d/m/Y') }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($loan->status === 'active') bg-blue-100 text-blue-800
                                    @elseif($loan->status === 'returned') bg-green-100 text-green-800
                                    @elseif($loan->status === 'overdue') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Active Loans -->
                @if($book->activeLoans->count() > 0)
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Préstamos Activos</h2>
                    <div class="space-y-3">
                        @foreach($book->activeLoans as $loan)
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div>
                                <p class="font-medium text-gray-900">{{ $loan->user->name }}</p>
                                <p class="text-sm text-gray-600">
                                    Prestado: {{ $loan->loan_date->format('d/m/Y') }}
                                    - Vence: {{ $loan->due_date->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                @can('manage-loans')
                                <div class="flex space-x-2 mb-2">
                                    <form method="POST" action="{{ route('loans.return', $loan) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded transition duration-150">
                                            Devolver
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('loans.extend', $loan) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition duration-150">
                                            Extender
                                        </button>
                                    </form>
                                </div>
                                @endcan
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Activo
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
