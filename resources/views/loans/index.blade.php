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
                            Mis Préstamos
                        @else
                            Gestión de Préstamos
                        @endif
                    </h1>
                    <p class="text-amber-700 mt-2">
                        @if(auth()->user()->isLector())
                            Consulta el estado de tus préstamos de libros
                        @else
                            Administra todos los préstamos de la biblioteca
                        @endif
                    </p>
                </div>
                @can('manage-loans')
                    <a href="{{ route('loans.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-medium rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nuevo Préstamo
                    </a>
                @endcan
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
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Activos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Vencidos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['overdue'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Devueltos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['returned'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-amber-800 mb-2">Estado</label>
                    <select name="status" class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Devuelto</option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Vencido</option>
                    </select>
                </div>

                @can('manage-loans')
                    <div>
                        <label class="block text-sm font-medium text-amber-800 mb-2">Usuario</label>
                        <select name="user_id" class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50">
                            <option value="">Todos los usuarios</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endcan

                <div>
                    <label class="block text-sm font-medium text-amber-800 mb-2">Buscar Libro</label>
                    <input type="text" name="book_search" value="{{ request('book_search') }}"
                           placeholder="Título o autor..."
                           class="w-full border border-amber-300 rounded-lg px-3 py-2 bg-white/50">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition duration-150">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Loans Table -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 overflow-hidden">
            @if($loans->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-amber-200">
                        <thead class="bg-amber-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Libro
                                </th>
                                @can('manage-loans')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                        Usuario
                                    </th>
                                @endcan
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Fechas
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/50 divide-y divide-amber-100">
                            @foreach($loans as $loan)
                                <tr class="hover:bg-amber-50/50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-8 bg-amber-200 rounded flex items-center justify-center flex-shrink-0">
                                                <svg class="h-5 w-5 text-amber-700" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $loan->book->title }}</div>
                                                <div class="text-sm text-gray-600">{{ $loan->book->author }}</div>
                                                <div class="text-xs text-amber-600">{{ $loan->book->category->name ?? 'Sin categoría' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    @can('manage-loans')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $loan->user->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $loan->user->email }}</div>
                                        </td>
                                    @endcan
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>Préstamo: {{ $loan->loan_date->format('d/m/Y') }}</div>
                                        <div class="{{ $loan->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                                            Vence: {{ $loan->due_date->format('d/m/Y') }}
                                        </div>
                                        @if($loan->return_date)
                                            <div class="text-green-600">Devuelto: {{ $loan->return_date->format('d/m/Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($loan->status === 'active')
                                            @if($loan->isOverdue())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Vencido ({{ $loan->getDaysOverdue() }} días)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Activo
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Devuelto
                                            </span>
                                        @endif

                                        @if($loan->hasPendingClaims())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-1">
                                                Con reclamo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('loans.show', $loan) }}"
                                           class="text-amber-600 hover:text-amber-900 transition duration-150">
                                            Ver
                                        </a>

                                        @if(auth()->user()->isLector() && $loan->canBeClaimed())
                                            <a href="{{ route('claims.create', ['loan_id' => $loan->id]) }}"
                                               class="text-blue-600 hover:text-blue-900 transition duration-150">
                                                Reclamar
                                            </a>
                                        @endif

                                        @can('manage-loans')
                                            @if($loan->status === 'active')
                                                <form action="{{ route('loans.return', $loan) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            onclick="return confirm('¿Confirmar devolución del libro?')"
                                                            class="text-green-600 hover:text-green-900 transition duration-150">
                                                        Devolver
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white/30 px-4 py-3 border-t border-amber-200">
                    {{ $loans->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-amber-900">No hay préstamos</h3>
                    <p class="mt-1 text-sm text-amber-600">
                        @if(auth()->user()->isLector())
                            Aún no tienes préstamos registrados.
                        @else
                            No se encontraron préstamos con los filtros aplicados.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
