<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bellitek | The Only Tech Store You Need</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-900">

  <!-- Navbar -->
  <nav class="bg-white shadow-md fixed top-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <a href="/" class="text-2xl font-bold text-red-600">Bellitek</a>
      <ul class="flex space-x-8 text-sm font-medium">
        <li><a href="/" class="hover:text-red-500">Home</a></li>
        <li><a href="/services" class="hover:text-red-500">Services</a></li>
        <li><a href="/track" class="hover:text-red-500">Track Repair</a></li>
        <li><a href="/contact" class="hover:text-red-500">Contact</a></li>
      </ul>
    </div>
  </nav>

  <main class="pt-24">
    @yield('content')
  </main>

  <footer class="bg-gray-900 text-gray-300 text-center py-6 mt-16">
    <p>© {{ date('Y') }} Bellitek. All rights reserved.</p>
  </footer>

</body>
</html>
