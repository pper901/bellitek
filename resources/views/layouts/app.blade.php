<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bellitek | The Only Tech Store You Need</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">

<!-- Navbar is fixed and outside the flow, which is fine -->
<nav class="bg-white shadow-md fixed top-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        
        <!-- Logo/Home Link -->
        <a href="/" class="text-2xl font-bold text-red-600">Bellitek</a>

        <!-- Primary Navigation Links (Left side) -->
        <ul class="flex space-x-8 text-sm font-medium">
            <li><a href="/" class="hover:text-red-500 transition duration-150">Home</a></li>
            <li><a href="/services" class="hover:text-red-500 transition duration-150">Services</a></li>
            
            {{-- New Guides Link --}}
            <li><a href="/guides" class="hover:text-red-500 transition duration-150">Guides</a></li> 
            
            {{-- New Store Link --}}
            <li><a href="/store" class="hover:text-red-500 transition duration-150">Store</a></li>
            
            <li><a href="/track" class="hover:text-red-500 transition duration-150">Track Repair</a></li>
            <li><a href="/contact" class="hover:text-red-500 transition duration-150">Contact</a></li>
        </ul>

        <!-- Authentication Links (Right side) -->
        <div class="flex space-x-4 text-sm font-medium">
            <a href="/login" class="text-gray-600 hover:text-red-600 transition duration-150 border-r pr-4">Login</a>
            <a href="/register" class="text-red-600 hover:text-red-800 transition duration-150 font-semibold">Sign Up</a>
        </div>
        
    </div>
</nav>

{{-- CRITICAL FIX: Added flex-grow to make this element fill all remaining vertical space --}}
<main class="pt-24 flex-grow"> 
    @yield('content')
</main>

<footer class="bg-gray-900 text-gray-300 text-center py-6 mt-16">
    <p>© {{ date('Y') }} Bellitek. All rights reserved.</p>
</footer>


</body>
</html>