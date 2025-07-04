<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Biblioteca Digital') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=crimson-text:400,600,700|inter:400,500,600" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'serif': ['Crimson Text', 'serif'],
                        'sans': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <style>
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

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: scale(1.02);
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
        }
    </style>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="font-sans text-gray-900 antialiased">
    @yield('content')

    <!-- Custom JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
