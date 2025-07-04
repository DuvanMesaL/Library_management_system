@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-amber-900 font-serif">Gestión de Invitaciones</h1>
                    <p class="text-amber-700 mt-2">Administra las invitaciones enviadas a nuevos usuarios</p>
                </div>
                <a href="{{ route('admin.invitations.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-medium rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nueva Invitación
                </a>
            </div>
        </div>

        <!-- Invitations Table -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 overflow-hidden">
            @if($invitations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-amber-200">
                        <thead class="bg-amber-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Rol
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Invitado por
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Expira
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/50 divide-y divide-amber-100">
                            @foreach($invitations as $invitation)
                                <tr class="hover:bg-amber-50/50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 bg-amber-100 rounded-full flex items-center justify-center">
                                                <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $invitation->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($invitation->role->name === 'admin') bg-red-100 text-red-800
                                            @elseif($invitation->role->name === 'bibliotecario') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($invitation->role->name) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $invitation->invitedBy->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($invitation->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($invitation->status === 'accepted') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            @if($invitation->status === 'pending') Pendiente
                                            @elseif($invitation->status === 'accepted') Aceptada
                                            @else Expirada @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $invitation->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $invitation->expires_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($invitation->status === 'pending')
                                            <form action="{{ route('admin.invitations.destroy', $invitation) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('¿Estás seguro de eliminar esta invitación?')"
                                                        class="text-red-600 hover:text-red-900 transition duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white/30 px-4 py-3 border-t border-amber-200">
                    {{ $invitations->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-amber-900">No hay invitaciones</h3>
                    <p class="mt-1 text-sm text-amber-600">Comienza enviando tu primera invitación.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.invitations.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-medium rounded-lg shadow-lg transition duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Nueva Invitación
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
