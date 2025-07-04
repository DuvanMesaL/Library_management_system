@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                    <h1 class="text-3xl font-bold text-amber-900 font-serif">Detalles del Reclamo</h1>
                    <p class="text-amber-700 mt-2">Reclamo #{{ $claim->id }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Claim Status and Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                    <!-- Status -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-amber-900 mb-3">Estado del Reclamo</h3>
                        <div class="text-center">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $claim->getStatusBadgeClass() }}">
                                @switch($claim->status)
                                    @case('pending')
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L10 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Pendiente
                                        @break
                                    @case('in_review')
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                        </svg>
                                        En Revisión
                                        @break
                                    @case('resolved')
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Resuelto
                                        @break
                                    @case('rejected')
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Rechazado
                                        @break
                                @endswitch
                            </span>
                        </div>

                        <!-- Priority -->
                        <div class="mt-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $claim->getPriorityBadgeClass() }}">
                                Prioridad: {{ ucfirst($claim->priority) }}
                            </span>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-amber-800 mb-3">Cronología</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L10 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Creado: {{ $claim->created_at->format('d/m/Y H:i') }}
                            </div>
                            @if($claim->resolved_at)
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Resuelto: {{ $claim->resolved_at->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Admin Actions -->
                    @can('manage-loans')
                        @if($claim->status !== 'resolved' && $claim->status !== 'rejected')
                            <div class="border-t border-amber-200 pt-4">
                                <h4 class="text-sm font-medium text-amber-800 mb-3">Acciones de Administrador</h4>

                                <form action="{{ route('claims.update', $claim) }}" method="POST" class="space-y-3">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <select name="status" required class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50 text-sm">
                                            <option value="pending" {{ $claim->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="in_review" {{ $claim->status === 'in_review' ? 'selected' : '' }}>En Revisión</option>
                                            <option value="resolved" {{ $claim->status === 'resolved' ? 'selected' : '' }}>Resuelto</option>
                                            <option value="rejected" {{ $claim->status === 'rejected' ? 'selected' : '' }}>Rechazado</option>
                                        </select>
                                    </div>

                                    <div>
                                        <textarea name="admin_response" rows="3"
                                                  class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50 text-sm"
                                                  placeholder="Respuesta al usuario...">{{ $claim->admin_response }}</textarea>
                                    </div>

                                    <button type="submit"
                                            class="w-full bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150">
                                        Actualizar Reclamo
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endcan
                </div>
            </div>

            <!-- Claim Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Claim Information -->
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                    <h2 class="text-xl font-semibold text-amber-900 mb-4">Información del Reclamo</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-amber-800">Asunto</label>
                            <p class="text-gray-900 font-medium">{{ $claim->subject }}</p>
                        </div>

                        @can('manage-loans')
                            <div>
                                <label class="block text-sm font-medium text-amber-800">Usuario</label>
                                <p class="text-gray-900">{{ $claim->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $claim->user->email }}</p>
                            </div>
                        @endcan

                        <div>
                            <label class="block text-sm font-medium text-amber-800">Descripción</label>
                            <div class="mt-2 p-4 bg-amber-50 rounded-lg border border-amber-200">
                                <p class="text-gray-700 leading-relaxed">{{ $claim->description }}</p>
                            </div>
                        </div>

                        @if($claim->assignedTo)
                            <div>
                                <label class="block text-sm font-medium text-amber-800">Asignado a</label>
                                <p class="text-gray-900">{{ $claim->assignedTo->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Loan -->
                <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                    <h2 class="text-xl font-semibold text-amber-900 mb-4">Préstamo Relacionado</h2>

                    <div class="flex items-center space-x-4">
                        <div class="h-20 w-16 bg-gradient-to-br from-amber-100 to-orange-100 rounded flex items-center justify-center flex-shrink-0">
                            @if($claim->loan->book->cover_image)
                                <img src="{{ $claim->loan->book->getCoverImageUrl() }}" alt="{{ $claim->loan->book->title }}"
                                     class="h-full w-full object-cover rounded">
                            @else
                                <svg class="h-10 w-10 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $claim->loan->book->title }}</h3>
                            <p class="text-gray-600">{{ $claim->loan->book->author }}</p>
                            <p class="text-sm text-amber-600">{{ $claim->loan->book->category->name ?? 'Sin categoría' }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                Préstamo: {{ $claim->loan->loan_date->format('d/m/Y') }} -
                                Vence: {{ $claim->loan->due_date->format('d/m/Y') }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('loans.show', $claim->loan) }}"
                               class="inline-flex items-center px-3 py-2 bg-amber-100 hover:bg-amber-200 text-amber-800 rounded-lg text-sm font-medium transition duration-150">
                                Ver Préstamo
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Admin Response -->
                @if($claim->admin_response)
                    <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                        <h2 class="text-xl font-semibold text-amber-900 mb-4">Respuesta del Administrador</h2>

                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-gray-700 leading-relaxed">{{ $claim->admin_response }}</p>
                            @if($claim->assignedTo)
                                <p class="text-sm text-gray-500 mt-3">
                                    Respondido por: {{ $claim->assignedTo->name }}
                                    @if($claim->resolved_at)
                                        el {{ $claim->resolved_at->format('d/m/Y H:i') }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
