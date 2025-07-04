@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                    <h1 class="text-3xl font-bold text-amber-900 font-serif">Detalles del Préstamo</h1>
                    <p class="text-amber-700 mt-2">Préstamo #{{ $loan->id }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Loan Status and Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                    <!-- Status -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-amber-900 mb-3">Estado del Préstamo</h3>
                        <div class="text-center">
                            @if($loan->status === 'active')
                                @if($loan->isOverdue())
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Vencido ({{ $loan->getDaysOverdue() }} días)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Activo
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    Devuelto
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        @if(auth()->user()->isLector() && $loan->canBeClaimed())
                            <a href="{{ route('claims.create', ['loan_id' => $loan->id]) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Crear Reclamo
                            </a>
                        @endif

                        @can('manage-loans')
                            <!-- Edit Loan Button - Only for Admins/Librarians -->
                            <a href="{{ route('loans.edit', $loan) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar Préstamo
                            </a>

                            @if($loan->status === 'active')
                                <form action="{{ route('loans.return', $loan) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            onclick="return confirm('¿Confirmar devolución del libro?')"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-lg transition duration-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Marcar como Devuelto
                                    </button>
                                </form>

                                <!-- Extend Loan Form -->
                                <form action="{{ route('loans.extend', $loan) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-3">
                                        <select name="extend_days" required class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50 text-sm">
                                            <option value="">Extender por...</option>
                                            <option value="7">7 días</option>
                                            <option value="14">14 días</option>
                                            <option value="21">21 días</option>
                                        </select>
                                    </div>
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-medium rounded-lg shadow-lg transition duration-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Extender Préstamo
                                    </button>
                                </form>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Loan Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Book Information -->
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                    <h2 class="text-xl font-semibold text-amber-900 mb-4">Información del Libro</h2>

                    <div class="flex items-center space-x-4">
                        <div class="h-20 w-16 bg-gradient-to-br from-amber-100 to-orange-100 rounded flex items-center justify-center flex-shrink-0">
                            @if($loan->book->cover_image)
                                <img src="{{ $loan->book->getCoverImageUrl() }}" alt="{{ $loan->book->title }}"
                                     class="h-full w-full object-cover rounded">
                            @else
                                <svg class="h-10 w-10 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $loan->book->title }}</h3>
                            <p class="text-gray-600">{{ $loan->book->author }}</p>
                            <p class="text-sm text-amber-600">{{ $loan->book->category->name ?? 'Sin categoría' }}</p>
                            @if($loan->book->isbn)
                                <p class="text-sm text-gray-500 font-mono">ISBN: {{ $loan->book->isbn }}</p>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('books.show', $loan->book) }}"
                               class="inline-flex items-center px-3 py-2 bg-amber-100 hover:bg-amber-200 text-amber-800 rounded-lg text-sm font-medium transition duration-150">
                                Ver Libro
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Loan Details -->
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                    <h2 class="text-xl font-semibold text-amber-900 mb-4">Detalles del Préstamo</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @can('manage-loans')
                            <div>
                                <label class="block text-sm font-medium text-amber-800">Usuario</label>
                                <p class="text-gray-900 font-medium">{{ $loan->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $loan->user->email }}</p>
                            </div>
                        @endcan

                        <div>
                            <label class="block text-sm font-medium text-amber-800">Fecha de Préstamo</label>
                            <p class="text-gray-900">{{ $loan->loan_date->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-amber-800">Fecha de Vencimiento</label>
                            <p class="text-gray-900 {{ $loan->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                                {{ $loan->due_date->format('d/m/Y') }}
                            </p>
                        </div>

                        @if($loan->return_date)
                            <div>
                                <label class="block text-sm font-medium text-amber-800">Fecha de Devolución</label>
                                <p class="text-gray-900 text-green-600">{{ $loan->return_date->format('d/m/Y') }}</p>
                            </div>
                        @endif
                    </div>

                    @if($loan->notes)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-amber-800 mb-2">Notas</label>
                            <p class="text-gray-700 bg-amber-50 p-3 rounded-lg border border-amber-200">{{ $loan->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Claims -->
                @if($loan->claims->count() > 0)
                    <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                        <h2 class="text-xl font-semibold text-amber-900 mb-4">Reclamos Asociados</h2>

                        <div class="space-y-3">
                            @foreach($loan->claims as $claim)
                                <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg border border-amber-100">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $claim->subject }}</p>
                                        <p class="text-sm text-gray-600">{{ $claim->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $claim->getStatusBadgeClass() }}">
                                            @switch($claim->status)
                                                @case('pending') Pendiente @break
                                                @case('in_review') En Revisión @break
                                                @case('resolved') Resuelto @break
                                                @case('rejected') Rechazado @break
                                            @endswitch
                                        </span>
                                        <a href="{{ route('claims.show', $claim) }}"
                                           class="text-amber-600 hover:text-amber-700 text-sm font-medium">
                                            Ver
                                        </a>
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
