@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('loans.index') }}"
                   class="text-amber-600 hover:text-amber-700 transition duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-amber-900 font-serif">Nuevo Préstamo</h1>
                    <p class="text-amber-700 mt-2">Registra un nuevo préstamo de libro</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-8">
            <form method="POST" action="{{ route('loans.store') }}" class="space-y-6">
                @csrf

                <!-- User Selection -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-amber-800 mb-2">
                        Usuario
                    </label>
                    <select id="user_id" name="user_id" required
                            class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Selecciona un usuario</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Book Selection -->
                <div>
                    <label for="book_id" class="block text-sm font-medium text-amber-800 mb-2">
                        Libro
                    </label>
                    <select id="book_id" name="book_id" required
                            class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Selecciona un libro</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->title }} - {{ $book->author }}
                                ({{ $book->copies_available }} disponibles)
                            </option>
                        @endforeach
                    </select>
                    @error('book_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Loan Duration -->
                <div>
                    <label for="loan_days" class="block text-sm font-medium text-amber-800 mb-2">
                        Duración del Préstamo (días)
                    </label>
                    <select id="loan_days" name="loan_days" required
                            class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Selecciona duración</option>
                        <option value="7" {{ old('loan_days') == '7' ? 'selected' : '' }}>7 días (1 semana)</option>
                        <option value="14" {{ old('loan_days') == '14' ? 'selected' : '' }}>14 días (2 semanas)</option>
                        <option value="21" {{ old('loan_days') == '21' ? 'selected' : '' }}>21 días (3 semanas)</option>
                        <option value="30" {{ old('loan_days') == '30' ? 'selected' : '' }}>30 días (1 mes)</option>
                    </select>
                    @error('loan_days')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-amber-800 mb-2">
                        Notas (Opcional)
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                              placeholder="Observaciones adicionales sobre el préstamo...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route('loans.index') }}"
                       class="px-4 py-2 border border-amber-300 rounded-lg text-amber-700 hover:bg-amber-50 transition duration-150">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-medium rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Crear Préstamo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
