<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Heroicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/heroicons@2.0.18/24/outline/style.css">

    <style>
        [x-cloak] { display: none !important; }

        .gradient-bg {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .btn-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-1px);
        }

        .bg-gradient-library {
            background: linear-gradient(135deg, #fef7ed 0%, #fed7aa 50%, #fbbf24 100%);
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.7);
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .text-shadow {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="gradient-bg shadow-lg" x-data="{ open: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0">
                            <a href="{{ route('dashboard') }}" class="text-white text-xl font-bold">
                                📚 Biblioteca
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden md:ml-10 md:flex md:space-x-8">
                            <a href="{{ route('dashboard') }}"
                               class="text-white hover:text-amber-100 px-3 py-2 rounded-md text-sm font-medium transition-colors
                                      {{ request()->routeIs('dashboard') ? 'bg-amber-600' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('books.index') }}"
                               class="text-white hover:text-amber-100 px-3 py-2 rounded-md text-sm font-medium transition-colors
                                      {{ request()->routeIs('books.*') ? 'bg-amber-600' : '' }}">
                                Libros
                            </a>
                            <a href="{{ route('loans.index') }}"
                               class="text-white hover:text-amber-100 px-3 py-2 rounded-md text-sm font-medium transition-colors
                                      {{ request()->routeIs('loans.*') ? 'bg-amber-600' : '' }}">
                                Préstamos
                            </a>
                            <a href="{{ route('claims.index') }}"
                               class="text-white hover:text-amber-100 px-3 py-2 rounded-md text-sm font-medium transition-colors
                                      {{ request()->routeIs('claims.*') ? 'bg-amber-600' : '' }}">
                                Reclamos
                            </a>
                            @can('invite-users')
                            <a href="{{ route('admin.invitations.index') }}"
                               class="text-white hover:text-amber-100 px-3 py-2 rounded-md text-sm font-medium transition-colors
                                      {{ request()->routeIs('admin.*') ? 'bg-amber-600' : '' }}">
                                Invitaciones
                            </a>
                            @endcan
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="hidden md:flex md:items-center md:space-x-4">
                        <!-- User Role Badge -->
                        <span class="bg-amber-600 text-white px-3 py-1 rounded-full text-xs font-medium">
                            {{ auth()->user()->role->name ?? 'Usuario' }}
                        </span>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="flex items-center text-white hover:text-amber-100 focus:outline-none focus:text-amber-100 transition-colors">
                                <span class="mr-2">{{ auth()->user()->name }}</span>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                                 x-cloak>
                                <div class="px-4 py-2 text-xs text-gray-400 border-b">
                                    {{ auth()->user()->email }}
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button @click="open = !open"
                                class="text-white hover:text-amber-100 focus:outline-none focus:text-amber-100">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="md:hidden bg-amber-600"
                 x-cloak>
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('dashboard') }}"
                       class="block text-white hover:text-amber-100 px-3 py-2 rounded-md text-base font-medium
                              {{ request()->routeIs('dashboard') ? 'bg-amber-700' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('books.index') }}"
                       class="block text-white hover:text-amber-100 px-3 py-2 rounded-md text-base font-medium
                              {{ request()->routeIs('books.*') ? 'bg-amber-700' : '' }}">
                        Libros
                    </a>
                    <a href="{{ route('loans.index') }}"
                       class="block text-white hover:text-amber-100 px-3 py-2 rounded-md text-base font-medium
                              {{ request()->routeIs('loans.*') ? 'bg-amber-700' : '' }}">
                        Préstamos
                    </a>
                    <a href="{{ route('claims.index') }}"
                       class="block text-white hover:text-amber-100 px-3 py-2 rounded-md text-base font-medium
                              {{ request()->routeIs('claims.*') ? 'bg-amber-700' : '' }}">
                        Reclamos
                    </a>
                    @can('invite-users')
                    <a href="{{ route('admin.invitations.index') }}"
                       class="block text-white hover:text-amber-100 px-3 py-2 rounded-md text-base font-medium
                              {{ request()->routeIs('admin.*') ? 'bg-amber-700' : '' }}">
                        Invitaciones
                    </a>
                    @endcan
                </div>
                <div class="pt-4 pb-3 border-t border-amber-700">
                    <div class="px-5">
                        <div class="text-white font-medium">{{ auth()->user()->name }}</div>
                        <div class="text-amber-200 text-sm">{{ auth()->user()->email }}</div>
                        <div class="mt-1">
                            <span class="bg-amber-700 text-white px-2 py-1 rounded text-xs">
                                {{ auth()->user()->role->name ?? 'Usuario' }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-3 px-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="block w-full text-left text-white hover:text-amber-100 px-3 py-2 rounded-md text-base font-medium">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Page Content -->
        <main class="py-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
