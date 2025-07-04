@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('loans.show', $loan) }}"
                   class="text-amber-600 hover:text-amber-700 transition duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-amber-900 font-serif">Editar Préstamo</h1>
                    <p class="text-amber-700 mt-2">Préstamo #{{ $loan->id }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Current Loan Info -->
            <div class="lg:col-span-1">
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                    <h3 class="text-lg font-semibold text-amber-900 mb-4">Información Actual</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-amber-800">Usuario Actual</label>
                            <p class="text-gray-900 font-medium">{{ $loan->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $loan->user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-amber-800">Libro Actual</label>
                            <p class="text-gray-900 font-medium">{{ $loan->book->title }}</p>
                            <p class="text-sm text-gray-600">{{ $loan->book->author }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-amber-800">Estado</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $loan->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-amber-800">Fecha de Préstamo</label>
                            <p class="text-gray-900">{{ $loan->loan_date->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-amber-800">Fecha de Vencimiento</label>
                            <p class="text-gray-900">{{ $loan->due_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="lg:col-span-2">
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                    <h2 class="text-xl font-semibold text-amber-900 mb-6">Modificar Préstamo</h2>

                    <form action="{{ route('loans.update', $loan) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- User Selection -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-amber-800 mb-2">Usuario</label>
                            <select name="user_id" id="user_id" required
                                    class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50 focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $loan->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Book Selection -->
                        <div>
                            <label for="book_id" class="block text-sm font-medium text-amber-800 mb-2">Libro</label>
                            <select name="book_id" id="book_id" required
                                    class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50 focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}"
                                            {{ $loan->book_id == $book->id ? 'selected' : '' }}
                                            data-available="{{ $book->copies_available }}">
                                        {{ $book->title }} - {{ $book->author }}
                                        @if($book->id != $loan->book_id)
                                            ({{ $book->copies_available }} disponibles)
                                        @else
                                            (Actual)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('book_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <div id="book-warning" class="hidden mt-2 p-3 bg-red-100 border border-red-300 rounded-lg">
                                <p class="text-red-700 text-sm">⚠️ Este libro no tiene copias disponibles.</p>
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-amber-800 mb-2">Fecha de Vencimiento</label>
                            <input type="date" name="due_date" id="due_date" required
                                   value="{{ $loan->due_date->format('Y-m-d') }}"
                                   min="{{ $loan->loan_date->format('Y-m-d') }}"
                                   class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50 focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            @error('due_date')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-amber-800 mb-2">Estado</label>
                            <select name="status" id="status" required
                                    class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50 focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                <option value="active" {{ $loan->status == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="returned" {{ $loan->status == 'returned' ? 'selected' : '' }}>Devuelto</option>
                                <option value="overdue" {{ $loan->status == 'overdue' ? 'selected' : '' }}>Vencido</option>
                            </select>
                            @error('status')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-amber-800 mb-2">Notas</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                      placeholder="Notas adicionales sobre el préstamo...">{{ $loan->notes }}</textarea>
                            @error('notes')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Warning Messages -->
                        <div id="status-warning" class="hidden p-4 bg-yellow-100 border border-yellow-300 rounded-lg">
                            <p class="text-yellow-700 text-sm">
                                <strong>⚠️ Advertencia:</strong> Cambiar el estado puede afectar la disponibilidad del libro en el inventario.
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <button type="submit"
                                    class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-lg transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Guardar Cambios
                            </button>

                            <a href="{{ route('loans.show', $loan) }}"
                               class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-medium rounded-lg shadow-lg transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookSelect = document.getElementById('book_id');
    const statusSelect = document.getElementById('status');
    const bookWarning = document.getElementById('book-warning');
    const statusWarning = document.getElementById('status-warning');
    const currentBookId = {{ $loan->book_id }};
    const currentStatus = '{{ $loan->status }}';

    // Check book availability
    function checkBookAvailability() {
        const selectedOption = bookSelect.options[bookSelect.selectedIndex];
        const available = parseInt(selectedOption.dataset.available);
        const selectedBookId = parseInt(bookSelect.value);

        if (selectedBookId !== currentBookId && available <= 0) {
            bookWarning.classList.remove('hidden');
        } else {
            bookWarning.classList.add('hidden');
        }
    }

    // Check status changes
    function checkStatusChange() {
        if (statusSelect.value !== currentStatus) {
            statusWarning.classList.remove('hidden');
        } else {
            statusWarning.classList.add('hidden');
        }
    }

    bookSelect.addEventListener('change', checkBookAvailability);
    statusSelect.addEventListener('change', checkStatusChange);

    // Initial checks
    checkBookAvailability();
    checkStatusChange();
});
</script>
@endsection
