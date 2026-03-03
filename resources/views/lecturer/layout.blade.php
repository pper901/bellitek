<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">

    <title>Lecturer Dashboard - {{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        
        <div class="flex flex-1">
            <aside class="w-64 bg-indigo-900 text-white hidden md:block shadow-xl">
                <div class="p-6 border-b border-indigo-800">
                    <span class="text-2xl font-bold tracking-wider">GeneralClass</span>
                </div>
                
                <nav class="mt-4 px-4 space-y-2">
                    <a href="/" class="flex items-center space-x-3 p-3 rounded-lg text-indigo-300 hover:bg-indigo-800 hover:text-white transition group">
                        <i class="fas fa-arrow-left w-5 group-hover:-translate-x-1 transition-transform"></i>
                        <span>Main Website</span>
                    </a>

                    <div class="pt-4 pb-2 text-xs font-semibold text-indigo-400 uppercase tracking-widest">
                        Management
                    </div>

                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800 transition' }}">
                        <i class="fas fa-home w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('lecturer.classes.create') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('lecturer.classes.create') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800 transition' }}">
                        <i class="fas fa-plus w-5"></i>
                        <span>New Class</span>
                    </a>
                    
                    <hr class="border-indigo-800 my-4">
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-red-600 transition text-left">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </aside>

            <div class="flex-1 flex flex-col">
                <header class="h-16 bg-white border-b flex items-center justify-between px-8">
                    <div class="flex items-center space-x-2 text-sm text-gray-400">
                        <a href="/" class="hover:text-indigo-600 transition">Bellitek</a>
                        <span>/</span>
                        <span class="text-gray-700 font-medium">@yield('header_title', 'Lecturer Station')</span>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-700 leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-indigo-500">Lecturer Account</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold border border-indigo-200">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </header>

                <main class="p-8">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>