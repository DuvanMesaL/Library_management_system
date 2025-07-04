@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('books.show', $book) }}"
                   class="text-amber-600 hover:text-amber-700 transition duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-amber-900 font-serif">Solicitar Préstamo</h1>
                    <p class="text-amber-700 mt-2">{{ $book->title }}</p>
                </div>
            </div>
        </div>

        <!-- Book Summary -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="h-20 w-16 bg-gradient-to-br from-amber-100 to-orange-100 rounded flex items-center justify-center flex-shrink-0">
                    @if($book->cover_image)
                        <img src="{{ $book->getCoverImageUrl() }}" alt="{{ $book->title }}"
                             class="h-full w-full object-cover rounded">
                    @else
                        <svg class="h-10 w-10 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">{{ $book->title }}</h3>
                    <p class="text-gray-600">{{ $book->author }}</p>
                    <p class="text-sm text-amber-600">{{ $book->category->name ?? 'Sin categoría' }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        {{ $book->copies_available }} copias disponibles
                    </p>
                </div>
            </div>
        </div>

        <!-- Loan Form -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-8">
            <form method="POST" action="{{ route('books.process-loan', $book) }}" class="space-y-6">
                @csrf

                <!-- Loan Duration -->
                <div>
                    <label for="loan_days" class="block text-sm font-medium text-amber-800 mb-2">
                        Duración del Préstamo
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
                    <p class="mt-2 text-sm text-amber-600">
                        La fecha de vencimiento se calculará automáticamente desde hoy.
                    </p>
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

                <!-- Terms and Conditions -->
                <div class="bg-amber-50 rounded-lg p-4 border border-amber-200">
                    <h4 class="text-sm font-medium text-amber-800 mb-3">Términos y Condiciones del Préstamo:</h4>
                    <ul class="text-sm text-amber-700 space-y-1">
                        <li>• El libro debe ser devuelto en la fecha acordada</li>
                        <li>• Los retrasos pueden generar restricciones en futuros préstamos</li>
                        <li>• Eres responsable del cuidado del libro durante el préstamo</li>
                        <li>• Puedes crear un reclamo si hay algún problema con el préstamo</li>
                        <li>• La biblioteca se reserva el derecho de solicitar la devolución anticipada</li>
                    </ul>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route('books.show', $book) }}"
                       class="px-4 py-2 border border-amber-300 rounded-lg text-amber-700 hover:bg-amber-50 transition duration-150">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Solicitar Préstamo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
