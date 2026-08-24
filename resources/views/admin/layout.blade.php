<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">
    <title>Admin Dashboard | Bellifix</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col">

        <div class="p-6 text-center text-2xl font-bold border-b border-gray-800">
            Bellifix Admin
        </div>

        <nav class="flex-1 p-4 space-y-2">

            <a href="{{ route('admin.dashboard') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
                🏠 Dashboard
            </a>

            <a href="{{ route('admin.guides.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.guides*') ? 'bg-gray-800' : '' }}">
                📚 Guides
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.products*') ? 'bg-gray-800' : '' }}">
                🛍 Products
            </a>

            <a href="{{ route('admin.repairs.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.repairs*') ? 'bg-gray-800' : '' }}">
                🧰 Repairs
            </a>

            <a href="{{ route('admin.repairLogs') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.repairLogs') ? 'bg-gray-800' : '' }}">
                🧾 Repair Logs
            </a>

            <a href="{{ route('admin.accounting.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.accounting*') ? 'bg-gray-800' : '' }}">
                💳 Accounting
            </a>

            <!-- Lecturers -->
            <a href="{{ route('admin.lecturers') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.lecturers*') ? 'bg-gray-800' : '' }}">
                👨‍🏫 Lecturers
            </a>

            <!-- Telemetry -->
            <a href="{{ route('admin.telemetry') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800 {{ request()->routeIs('admin.telemetry*') ? 'bg-gray-800' : '' }}">
                📊 Telemetry
            </a>

        </nav>

        <div class="p-4 border-t border-gray-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit"
                        class="w-full text-left px-3 py-2 rounded hover:bg-gray-800">
                    🚪 Logout
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-y-auto">

        <header class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">
                @yield('title')
            </h1>

            <div class="text-gray-700">
                Welcome, {{ auth()->user()->name }}
            </div>
        </header>

        @yield('content')

    </main>

</body>
</html>