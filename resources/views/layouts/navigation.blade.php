<nav x-data="{ open: false }" class="bg-gray-900 border-b border-yellow-400 shadow-lg shadow-gray-200/50 sticky top-0 z-50">



    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}">
                    <!-- <x-application-logo class="block h-9 w-auto text-yellow-400" /> -->
                    <img src="{{ asset('logo/logo1.png') }}" width="200" height="100" alt="Logo">
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden sm:flex sm:items-center space-x-6">
                @auth
                    <x-nav-link :href="route('leaderboard')" :active="request()->routeIs('leaderboard')"
                        class="text-yellow-400 hover:text-yellow-300 transition">
                        🏆 Leaderboard
                    </x-nav-link>

                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-yellow-400 hover:text-yellow-300 transition">
                                <div>{{ Auth::user()->name }}</div>
                                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill="currentColor" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">👤 Profile</x-dropdown-link>
                            <x-dropdown-link :href="route('myresults')">📊 My Results</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    🚪 Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                     <x-nav-link :href="route('about')" class="text-yellow-400 hover:text-yellow-300">ℹ️ About Bodmas</x-nav-link>
                    <x-nav-link :href="route('login')" class="text-yellow-400 hover:text-yellow-300">🔑 Log In</x-nav-link>
                    <x-nav-link :href="route('register')" class="text-yellow-400 hover:text-yellow-300">📝 Register</x-nav-link>
                @endauth

                @admin
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-yellow-400 hover:text-yellow-300 transition">
                                <div>⚙️ Manage</div>
                                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill="currentColor" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('admins')">👨‍💼 Admins</x-dropdown-link>
                            <x-dropdown-link :href="route('questions')">❓ Questions</x-dropdown-link>
                            <x-dropdown-link :href="route('quizzes')">📝 Quizzes</x-dropdown-link>
                            <x-dropdown-link :href="route('tests')">📊 Tests</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                @endadmin
            </div>

            <!-- Mobile Menu Button -->
            <div class="sm:hidden">
                <button @click="open = !open" class="text-yellow-400 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-gray-800 text-yellow-400 py-4">
        @auth
            <x-nav-link :href="route('profile.edit')">👤 Profile</x-nav-link>
            <x-nav-link :href="route('myresults')">📊 My Results</x-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    🚪 Log Out
                </x-nav-link>
            </form>
        @else
            <x-nav-link :href="route('login')">🔑 Log In</x-nav-link>
            <x-nav-link :href="route('register')">📝 Register</x-nav-link>
        @endauth

        @admin
            <div class="mt-2 border-t border-yellow-500 pt-2">
                <p class="px-4 text-yellow-300">⚙️ Admin Panel</p>
                <x-nav-link :href="route('admins')">👨‍💼 Admins</x-nav-link>
                <x-nav-link :href="route('questions')">❓ Questions</x-nav-link>
                <x-nav-link :href="route('quizzes')">📝 Quizzes</x-nav-link>
                <x-nav-link :href="route('tests')">📊 Tests</x-nav-link>
            </div>
        @endadmin
    </div>
</nav>
