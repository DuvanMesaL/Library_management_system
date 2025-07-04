@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        &lt;!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-amber-100 rounded-full flex items-center justify-center">
                <svg class="h-10 w-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-bold text-amber-900 font-serif">Verifica tu Email</h2>
            <p class="mt-2 text-sm text-amber-700">Hemos enviado un enlace de verificación a tu correo</p>
        </div>

        &lt;!-- Content -->
        <div class="rounded-lg bg-white/70 backdrop-blur-sm shadow-xl p-8 border border-amber-200">
            <div class="text-center space-y-4">
                <div class="bg-amber-50 rounded-lg p-4 border border-amber-200">
                    <p class="text-sm text-amber-800">
                        Antes de continuar, por favor revisa tu email para encontrar el enlace de verificación.
                    </p>
                </div>

                <p class="text-sm text-gray-600">
                    Si no recibiste el email, puedes solicitar otro.
                </p>

                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition duration-150 ease-in-out">
                        Reenviar Email de Verificación
                    </button>
                </form>

                <div class="pt-4">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-amber-600 hover:text-amber-700 transition duration-150">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
