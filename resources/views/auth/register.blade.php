<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-6">
        <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8">
            <h1 class="text-3xl font-bold text-center text-red-600 mb-6">Create Your Bellitek Account</h1>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full border border-gray-300 rounded-lg mt-1 p-3 focus:ring-2 focus:ring-red-500 focus:outline-none" />
                    @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
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

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full border border-gray-300 rounded-lg mt-1 p-3 focus:ring-2 focus:ring-red-500 focus:outline-none" />
                </div>

                <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition">
                    Create Account
                </button>

                <p class="text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-red-500 hover:underline">Log in</a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>
