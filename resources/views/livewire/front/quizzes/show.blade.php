<div x-data="{ secondsLeft: {{ config('quiz.secondsPerQuestion') }} }" 
    x-init="setInterval(() => {
        if (secondsLeft > 1) { secondsLeft--; } else {
            secondsLeft = {{ config('quiz.secondsPerQuestion') }};
            $wire.nextQuestion();
        }
    }, 1000);" class="max-w-4xl mx-auto p-4">
    
    <div id="timer" class="text-red-600 font-bold mb-4 text-center text-lg"></div>
    
    <div class="mb-4 text-center bg-yellow-100 p-3 rounded-lg shadow warning-box">
        <p class="text-red-500 font-bold text-sm md:text-base">
            ⚠️ Do not refresh the page during the test! Be careful before selecting an answer because once selected or skipped, you cannot change it.
        </p>
        <p class="mt-2 text-lg font-semibold">Time left for this question: <span x-text="secondsLeft" class="font-bold"></span> sec.</p>
    </div>
    
    <span class="text-lg font-bold block text-center">Question {{ $currentQuestionIndex + 1 }} of {{ $this->questionsCount }}:</span>
    <h2 class="mb-4 text-xl md:text-2xl text-center">{!! $currentQuestion->text !!}</h2>
    
    @if ($currentQuestion->file)
    <div class="mt-2 mb-2 flex justify-center">
        <img src="{{ asset('storage/' . $currentQuestion->file) }}" alt="Question Image" class="max-w-full h-auto rounded-lg shadow-lg">
    </div>
    @endif
    
    @if ($currentQuestion->code_snippet)
    <pre class="mb-4 border-2 border-gray-300 bg-gray-50 p-3 text-sm md:text-base overflow-auto">{{ $currentQuestion->code_snippet }}</pre>
    @endif
    
    <div class="space-y-3">
        @foreach ($currentQuestion->options as $option)
        <div class="flex items-center bg-gray-100 p-3 rounded-lg shadow-md">
            <input type="radio" id="option.{{ $option->id }}"
                wire:model.defer="answersOfQuestions.{{ $currentQuestionIndex }}"
                name="answersOfQuestions.{{ $currentQuestionIndex }}" value="{{ $option->id }}"
                class="mr-2 w-5 h-5">
            <label for="option.{{ $option->id }}" class="text-base md:text-lg">&nbsp;&nbsp;{!!  $option->text !!}</label>
        </div>
        @endforeach
    </div>
    
    <div class="mt-6 flex flex-col md:flex-row justify-center md:justify-between gap-3">
        @if ($currentQuestionIndex > 0)
        <x-secondary-button x-on:click="secondsLeft = {{ config('quiz.secondsPerQuestion') }}; $wire.previousQuestion();" class=" md:w-auto text-center">
            Previous Question
        </x-secondary-button>
        @endif
    
        @if ($currentQuestionIndex < $this->questionsCount - 1)
        <x-secondary-button x-on:click="secondsLeft = {{ config('quiz.secondsPerQuestion') }}; $wire.nextQuestion();" class=" md:w-auto text-center">
            Next Question
        </x-secondary-button>
        @else
        <x-primary-button id="submit-button" x-on:click="$wire.submit();" class="w-full md:w-auto text-center">
            Submit
        </x-primary-button>
        @endif
    </div>
</div>

<script>
    let timeLeft = 1800; // 30 minutes in seconds
    const timerElement = document.getElementById('timer');

    const timerInterval = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `Time left: ${minutes} minutes ${seconds} seconds`;
        timeLeft--;
        
        if (timeLeft < 0) {
            clearInterval(timerInterval);
            alert('Time is up! Your quiz will be submitted automatically.');
            document.getElementById('submit-button')?.click();
        }
    }, 1000);
    
    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = '';
        return 'Are you sure you want to leave? Your progress will be lost.';
    });
</script>
<style>
    .quiz-container {
    max-width: 800px;
    background: #FFFFFF;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
#timer {
    font-size: 20px;
    font-weight: bold;
    color: #D32F2F;
    text-align: center;
    padding: 10px;
}
.warning-box {
    background: #FFF3CD;
    color: #856404;
    padding: 12px;
    border-left: 5px solid #FFC107;
    border-radius: 8px;
}

</style>
