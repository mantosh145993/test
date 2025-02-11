<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Main Container with Dark Background -->
    <div class="max-w-md mx-auto bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-700">
        <h2 class="text-2xl font-semibold text-yellow-400 text-center mb-4">Welcome Back</h2>
        
        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-white" />
                <x-text-input id="email" class="block mt-1 w-full rounded-md border-gray-700 text-white bg-gray-900 focus:border-yellow-400 focus:ring-yellow-400"
                    type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="text-white" />
                <x-text-input id="password" class="block mt-1 w-full rounded-md border-gray-700 text-white bg-gray-900 focus:border-yellow-400 focus:ring-yellow-400"
                    type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mt-4">
                <input id="remember_me" type="checkbox" class="rounded border-gray-700 text-yellow-400 shadow-sm focus:ring-yellow-400" name="remember">
                <label for="remember_me" class="ml-2 text-sm text-white">Remember me</label>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between mt-4">
                <div class="text-sm">
                    <a class="text-yellow-400 hover:text-yellow-300 transition" href="{{ route('register') }}">Register</a>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a class="text-yellow-400 hover:text-yellow-300 transition" href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    </div>
                @endif
            </div>

            <!-- Login Button -->
            <div class="mt-6">
                <x-primary-button class="w-full bg-yellow-400 hover:bg-yellow-500 transition py-2 rounded-md text-gray-900 text-center">
                    Log in
                </x-primary-button>
            </div>
        </form>

        <!-- Social Login Links -->
        <div class="mt-4">
            <x-social-links />
        </div>
    </div>
</x-guest-layout>
