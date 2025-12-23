<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">

    <title>Bellitek | The Only Tech Store You Need</title>
    {{-- Ensure Alpine.js is loaded via app.js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(isset($seo))
        @include('components.seo', ['seo' => $seo])
    @endif

</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">

{{-- 
| 1. Main Navigation Wrapper 
| x-data="{ open: false }" manages the mobile menu state.
| @click.outside="open = false" closes the menu if the user clicks anywhere else.
--}}
<nav x-data="{ open: false }" class="bg-white shadow-lg fixed top-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            
            <a href="/" class="text-2xl font-bold text-red-600">Bellitek</a>

            {{-- 
            | 2. Desktop Navigation & Auth/Cart - Visible on medium screens and up
            | Hidden on small screens to make room for the hamburger menu.
            --}}
            <div class="hidden md:flex md:items-center md:space-x-8">

                <ul class="flex space-x-6 lg:space-x-8 text-sm font-medium">
                    <li><a href="/" class="hover:text-red-600 transition duration-150">Home</a></li>
                    <li><a href="/services" class="hover:text-red-600 transition duration-150">Services</a></li>
                    <li><a href="/guides" class="hover:text-red-600 transition duration-150">Guides</a></li> 
                    <li><a href="/store" class="hover:text-red-600 transition duration-150">Store</a></li>
                    <li><a href="/repair/book" class="hover:text-red-600 transition duration-150">Track Repair</a></li>
                    <li><a href="/contact" class="hover:text-red-600 transition duration-150">Contact</a></li>
                </ul>

                <div class="flex space-x-4 text-sm font-medium items-center ml-6 border-l pl-6">
                    @auth
                        {{-- Dropdown is wrapped in x-data, which is correct --}}
                        <div x-data="{ drop_open: false }" class="relative">
                            <button @click="drop_open = !drop_open" class="text-gray-700 hover:text-red-600 flex items-center">
                                {{ auth()->user()->name }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="drop_open" @click.outside="drop_open = false" 
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-md overflow-hidden z-50">

                                <a href="/account" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Account Details</a>
                                <a href="/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Orders</a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="/login" class="text-gray-600 hover:text-red-600 transition duration-150 border-r pr-4">Login</a>
                        <a href="/register" class="text-red-600 hover:text-red-800 transition duration-150 font-semibold">Sign Up</a>
                    @endauth

                    <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-red-600">
                        {{-- Simplified Cart SVG to avoid massive block of code --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        
                        @php
                            // Note: This needs to be available globally or passed in if optimized, but kept for context.
                            $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('qty');
                        @endphp

                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                </div>
            </div>

            {{-- 
            | 3. Mobile Menu Button - Visible on mobile screens only 
            --}}
            <div class="flex items-center md:hidden">
                {{-- Cart Icon (Always visible on mobile) --}}
                <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-red-600 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                
                <button @click="open = !open" type="button" class="text-gray-500 hover:text-red-600 focus:outline-none">
                    {{-- Hamburger/X Icon --}}
                    <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
        </div>
    </div>

    {{-- 
    | 4. Mobile Menu Drawer (Displayed vertically below the navbar on mobile)
    --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-y-0 transform origin-top"
         x-transition:enter-end="opacity-100 scale-y-100 transform origin-top"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-y-100 transform origin-top"
         x-transition:leave-end="opacity-0 scale-y-0 transform origin-top"
         class="md:hidden border-t border-gray-200"
         @click.outside="open = false" {{-- Closes if clicked outside --}}
    >
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            {{-- Navigation Links --}}
            <a href="/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-150">Home</a>
            <a href="/services" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-150">Services</a>
            <a href="/guides" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-150">Guides</a>
            <a href="/store" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-150">Store</a>
            <a href="/repair/book" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-150">Track Repair</a>
            <a href="/contact" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-150">Contact</a>
        </div>
        
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4 space-y-1">
                @auth
                    <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                    <a href="/account" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">Account Details</a>
                    <a href="/orders" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">My Orders</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left block px-3 py-2 text-sm text-red-600 hover:bg-red-100 rounded-md">Logout</button>
                    </form>
                @else
                    <a href="/login" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 rounded-md">Login</a>
                    <a href="/register" class="block px-3 py-2 text-base font-medium text-red-600 hover:bg-red-50 rounded-md">Sign Up</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- CRITICAL FIX: Added flex-grow to make this element fill all remaining vertical space --}}
<main class="pt-[76px] flex-grow"> {{-- Adjusted pt-24 to pt-[76px] to account for fixed navbar height --}}
    @yield('content')
</main>

<footer class="bg-gray-900 text-gray-300 text-center py-6 mt-16">
    <p>© {{ date('Y') }} Bellitek. All rights reserved.</p>
</footer>


</body>
</html>