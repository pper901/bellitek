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

<footer class="bg-gray-900 text-gray-300 pt-12 pb-8 mt-16 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            
            <div class="text-center md:text-left">
                <h2 class="text-2xl font-bold text-white mb-2">Bellitek</h2>
                <p class="text-sm text-gray-400 leading-relaxed">
                    The only tech store you need for premium devices, expert repairs, and technical guides.
                </p>
                <div class="mt-4 pt-4 border-t border-gray-800">
                    <p class="text-xs uppercase tracking-widest text-gray-500 font-semibold mb-1">Registered Entity</p>
                    <p class="text-sm font-medium text-gray-300">RAFAT Technology Ltd</p>
                    <p class="text-[10px] text-gray-500 mt-1 italic">RC No: 9164382</p>
                </div>
            </div>

            <div class="text-center">
                <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/services" class="hover:text-red-500 transition">Services</a></li>
                    <li><a href="/store" class="hover:text-red-500 transition">Online Store</a></li>
                    <li><a href="/repair/book" class="hover:text-red-500 transition">Track Repair</a></li>
                    <li><a href="/contact" class="hover:text-red-500 transition">Support</a></li>
                </ul>
            </div>

            <div class="text-center md:text-right">
                <h3 class="text-white font-semibold mb-4">Connect With Us</h3>
                <div class="flex justify-center md:justify-end space-x-4">
                    <a href="#" class="bg-gray-800 p-2 rounded-full hover:bg-red-600 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                    <a href="#" class="bg-gray-800 p-2 rounded-full hover:bg-red-600 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 text-center">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} Bellitek. All rights reserved. Managed by RAFAT Technology Ltd.
            </p>
        </div>
    </div>
</footer>


</body>
</html>