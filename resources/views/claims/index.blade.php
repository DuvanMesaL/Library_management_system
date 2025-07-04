@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-amber-900 font-serif">
                        @if(auth()->user()->isLector())
                            Mis Reclamos
                        @else
                            Gestión de Reclamos
                        @endif
                    </h1>
                    <p class="text-amber-700 mt-2">
                        @if(auth()->user()->isLector())
                            Consulta el estado de tus reclamos enviados
                        @else
                            Administra todos los reclamos de usuarios
                        @endif
                    </p>
                </div>
                @if(auth()->user()->isLector())
                    <a href="{{ route('claims.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nuevo Reclamo
                    </a>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pendientes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">En Revisión</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['in_review'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Resueltos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['resolved'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-amber-800 mb-2">Estado</label>
                    <select name="status" class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50">
                        <option value="">Todos los estados</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>En Revisión</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resuelto</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-amber-800 mb-2">Prioridad</label>
                    <select name="priority" class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50">
                        <option value="">Todas las prioridades</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Baja</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Media</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Alta</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition duration-150">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Claims Table -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 overflow-hidden">
            @if($claims->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-amber-200">
                        <thead class="bg-amber-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Reclamo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Libro
                                </th>
                                @can('manage-loans')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                        Usuario
                                    </th>
                                @endcan
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/50 divide-y divide-amber-100">
                            @foreach($claims as $claim)
                                <tr class="hover:bg-amber-50/50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $claim->subject }}</div>
                                            <div class="text-sm text-gray-600">{{ Str::limit($claim->description, 60) }}</div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $claim->getPriorityBadgeClass() }} mt-1">
                                                {{ ucfirst($claim->priority) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $claim->loan->book->title }}</div>
                                        <div class="text-sm text-gray-600">{{ $claim->loan->book->author }}</div>
                                    </td>
                                    @can('manage-loans')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $claim->user->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $claim->user->email }}</div>
                                        </td>
                                    @endcan
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $claim->getStatusBadgeClass() }}">
                                            @switch($claim->status)
                                                @case('pending') Pendiente @break
                                                @case('in_review') En Revisión @break
                                                @case('resolved') Resuelto @break
                                                @case('rejected') Rechazado @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $claim->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('claims.show', $claim) }}"
                                           class="text-amber-600 hover:text-amber-900 transition duration-150">
                                            Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white/30 px-4 py-3 border-t border-amber-200">
                    {{ $claims->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-amber-900">No hay reclamos</h3>
                    <p class="mt-1 text-sm text-amber-600">
                        @if(auth()->user()->isLector())
                            Aún no has enviado ningún reclamo.
                        @else
                            No se encontraron reclamos con los filtros aplicados.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
