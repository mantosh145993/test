<div x-data="{ secondsLeft: {{ config('quiz.secondsPerQuestion') }} }" x-init="setInterval(() => {
    if (secondsLeft > 1) { secondsLeft--; } else {
        secondsLeft = {{ config('quiz.secondsPerQuestion') }};
        $wire.nextQuestion();
    }
}, 1000);">
    <div id="timer" class="text-red-600 font-bold mb-4"></div>
    <div class="mb-2">
    <p class="text-red-500 font-bold">
    ⚠️ Do not refresh the page during the test! Be careful before selecting an answer because once selected or skipped, you cannot change it.
</p>
        <br> Time left for this question: <span x-text="secondsLeft" class="font-bold"></span> sec.
    </div>

    <span class="text-bold">Question {{ $currentQuestionIndex + 1 }} of {{ $this->questionsCount }}:</span>
    <h2 class="mb-4 text-2xl">{!! $currentQuestion->text !!}</h2>

    <!-- Display image if file exists -->
    @if ($currentQuestion->file)
    <div class="mt-2 mb-2">
        <img src="{{ asset('storage/' . $currentQuestion->file) }}" alt="Question Image" class="img-responsive" width="300" height="200">
    </div>
    @endif

    @if ($currentQuestion->code_snippet)
    <pre class="mb-4 border-2 border-solid bg-gray-50 p-2">{{ $currentQuestion->code_snippet }}</pre>
    @endif
    @foreach ($currentQuestion->options as $option)
    <div>
        <label for="option.{{ $option->id }}">
            <input type="radio" id="option.{{ $option->id }}"
                wire:model.defer="answersOfQuestions.{{ $currentQuestionIndex }}"
                name="answersOfQuestions.{{ $currentQuestionIndex }}" value="{{ $option->id }}">
            <span>{!! $option->text !!}</span>
        </label>
    </div>
@endforeach

    <div class="mt-4 flex gap-2">
        <!-- Previous Button -->
        @if ($currentQuestionIndex > 0)
        <x-secondary-button x-on:click="secondsLeft = {{ config('quiz.secondsPerQuestion') }}; $wire.previousQuestion();">
            Previous Question
        </x-secondary-button>
        @endif

        <!-- Next Button -->
        @if ($currentQuestionIndex < $this->questionsCount - 1)
            <x-secondary-button
                x-on:click="secondsLeft = {{ config('quiz.secondsPerQuestion') }}; $wire.nextQuestion();">
                Next Question
            </x-secondary-button>
            @else
            <x-primary-button id="submit-button" x-on:click="$wire.submit();">Submit</x-primary-button>
            @endif
    </div>
</div>
<style>
    label {
    display: flex;
    align-items: center;
    gap: 8px; /* Adjust space between radio and text */
}

input[type="radio"] {
    margin: 0;
}

</style>
<script>
    let timeLeft = 1800; // 30 minutes in seconds
    const timerElement = document.getElementById('timer');

    const timerInterval = setInterval(() => {
        // Calculate minutes and seconds
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;

        // Update the timer element
        timerElement.textContent = `Time left: ${minutes} minutes ${seconds} seconds`;

        timeLeft--;

        if (timeLeft < 0) {
            clearInterval(timerInterval);
            alert('Time is up! Your quiz will be submitted automatically.');
            const submitButton = document.getElementById('submit-button');
            if (submitButton) {
                submitButton.click(); // Trigger the button's click event
            }
        }
    }, 1000);

    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = '';
        return 'Are you sure you want to leave? Your progress will be lost.';
    });
</script>
