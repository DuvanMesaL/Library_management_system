@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('claims.index') }}"
                   class="text-amber-600 hover:text-amber-700 transition duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-amber-900 font-serif">Crear Reclamo</h1>
                    <p class="text-amber-700 mt-2">Reporta un problema con tu préstamo</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-8">
            <form method="POST" action="{{ route('claims.store') }}" class="space-y-6">
                @csrf

                <!-- Loan Selection -->
                <div>
                    <label for="loan_id" class="block text-sm font-medium text-amber-800 mb-2">
                        Préstamo *
                    </label>
                    @if($loan)
                        <!-- Pre-selected loan -->
                        <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                        <div class="p-4 bg-amber-50 rounded-lg border border-amber-200">
                            <div class="flex items-center space-x-4">
                                <div class="h-16 w-12 bg-gradient-to-br from-amber-100 to-orange-100 rounded flex items-center justify-center flex-shrink-0">
                                    <svg class="h-8 w-8 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $loan->book->title }}</h3>
                                    <p class="text-gray-600">{{ $loan->book->author }}</p>
                                    <p class="text-sm text-amber-600">
                                        Préstamo del {{ $loan->loan_date->format('d/m/Y') }} - Vence: {{ $loan->due_date->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Loan selection dropdown -->
                        <select id="loan_id" name="loan_id" required
                                class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <option value="">Selecciona un préstamo</option>
                            @foreach($availableLoans as $availableLoan)
                                <option value="{{ $availableLoan->id }}" {{ old('loan_id') == $availableLoan->id ? 'selected' : '' }}>
                                    {{ $availableLoan->book->title }} - {{ $availableLoan->book->author }}
                                    ({{ $availableLoan->loan_date->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                        @if($availableLoans->count() === 0)
                            <p class="mt-2 text-sm text-amber-600">
                                No tienes préstamos activos disponibles para reclamar.
                                <a href="{{ route('loans.index') }}" class="font-medium hover:underline">Ver mis préstamos</a>
                            </p>
                        @endif
                    @endif
                    @error('loan_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-amber-800 mb-2">
                        Asunto *
                    </label>
                    <input type="text" id="subject" name="subject" required
                           class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                           placeholder="Resumen breve del problema" value="{{ old('subject') }}">
                    @error('subject')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-amber-800 mb-2">
                        Prioridad *
                    </label>
                    <select id="priority" name="priority" required
                            class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Selecciona la prioridad</option>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Baja - Consulta general</option>
                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Media - Problema moderado</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Alta - Problema urgente</option>
                    </select>
                    @error('priority')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-amber-800 mb-2">
                        Descripción del Problema *
                    </label>
                    <textarea id="description" name="description" rows="5" required
                              class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                              placeholder="Describe detalladamente el problema o solicitud...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-amber-600">
                        Proporciona todos los detalles posibles para ayudarnos a resolver tu problema más rápidamente.
                    </p>
                </div>

                <!-- Common Issues Examples -->
                <div class="bg-amber-50 rounded-lg p-4 border border-amber-200">
                    <h4 class="text-sm font-medium text-amber-800 mb-3">Ejemplos de reclamos comunes:</h4>
                    <ul class="text-sm text-amber-700 space-y-1">
                        <li>• Error en la fecha de vencimiento del préstamo</li>
                        <li>• Libro recibido en mal estado</li>
                        <li>• Solicitud de extensión del préstamo</li>
                        <li>• Problema con la devolución del libro</li>
                        <li>• Libro diferente al solicitado</li>
                        <li>• Consulta sobre el estado del préstamo</li>
                    </ul>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route('claims.index') }}"
                       class="px-4 py-2 border border-amber-300 rounded-lg text-amber-700 hover:bg-amber-50 transition duration-150">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Enviar Reclamo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
