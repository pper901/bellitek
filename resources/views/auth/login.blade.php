<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-6">
        <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8">
            <h1 class="text-3xl font-bold text-center text-red-600 mb-6">Bellitek Login</h1>

            @if (session('status'))
                <div class="mb-4 text-green-600 text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border border-gray-300 rounded-lg mt-1 p-3 focus:ring-2 focus:ring-red-500 focus:outline-none" />
                    @error('email') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg mt-1 p-3 focus:ring-2 focus:ring-red-500 focus:outline-none" />
                    @error('password') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="text-red-500 focus:ring-red-500 rounded">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-red-500 hover:underline" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition">
                    Log in
                </button>

                <p class="text-center text-sm text-gray-600">
                    Don’t have an account?
                    <a href="{{ route('register') }}" class="text-red-500 hover:underline">Sign up</a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>
