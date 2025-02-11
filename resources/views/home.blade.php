<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-20">

            <!-- Public Quizzes Section -->
            <div class="bg-gray-800 text-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-yellow-400">📖 Public Quizzes</h2>
                    <p class="text-gray-300">Test your knowledge with these quizzes.</p>
                    
                    <div class="flex flex-wrap gap-4 mt-4">
                        @forelse($public_quizzes as $quiz)
                            <div class="w-full sm:w-6/12 lg:w-3/12">
                                <div class="bg-gray-700 hover:bg-gray-600 transition duration-300 rounded-lg shadow-md p-4">
                                    <a href="{{ route('quiz.show', $quiz->slug) }}" class="text-yellow-300 font-semibold hover:underline">
                                        {{ $quiz->title }}
                                    </a>
                                    <p class="text-gray-300 text-sm mt-1">Questions: <span>{{ $quiz->questions_count }}</span></p>
                                </div>
                            </div>
                        @empty
                            <p class="mt-4 text-gray-400">No public quizzes found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Registered Users' Quizzes Section -->
            <div class="bg-gray-800 text-white shadow-lg rounded-lg overflow-hidden mt-8">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-yellow-400">🔐 Registered Users' Quizzes</h2>
                    <p class="text-gray-300">Exclusive quizzes for registered members.</p>
                    
                    <div class="flex flex-wrap gap-4 mt-4">
                        @forelse($registered_only_quizzes as $quiz)
                            <div class="w-full sm:w-6/12 lg:w-3/12">
                                <div class="bg-gray-700 hover:bg-gray-600 transition duration-300 rounded-lg shadow-md p-4">
                                    <a href="{{ route('quiz.show', $quiz->slug) }}" class="text-yellow-300 font-semibold hover:underline">
                                        {{ $quiz->title }}
                                    </a>
                                    <p class="text-gray-300 text-sm mt-1">Questions: <span>{{ $quiz->questions_count }}</span></p>
                                </div>
                            </div>
                        @empty
                            <p class="mt-4 text-gray-400">No quizzes for registered users found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
