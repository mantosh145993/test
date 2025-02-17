<?php

namespace App\Http\Livewire\Front\Quizzes;

use App\Models\Question;
use App\Models\Option;
use App\Models\Quiz;
use App\Models\Test;
use App\Models\Answer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Show extends Component
{
    public Quiz $quiz;
    public $timeLeft = 120;
    public Collection $questions;

    public Question $currentQuestion;
    public int $currentQuestionIndex = 0;

    public array $answersOfQuestions = [];

    public int $startTimeInSeconds = 0;

    public function mount()
    {
        $this->startTimeInSeconds = now()->timestamp;

        $this->questions = Question::query()
            ->inRandomOrder()
            ->whereRelation('quizzes', 'id', $this->quiz->id)
            ->with('options')
            ->get();

        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];

        for ($questionIndex = 0; $questionIndex < $this->questionsCount; $questionIndex++) {
            $this->answersOfQuestions[$questionIndex] = [];
        }
    }

    public function getQuestionsCountProperty(): int
    {
        return $this->questions->count();
    }

    public function nextQuestion()
    {
        $this->currentQuestionIndex++;

        if ($this->currentQuestionIndex == $this->questionsCount) {
            return $this->submit();
        }

        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
    }

    public function previousQuestion()
    {
        // Ensure we don't go below the first question
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
        }
    }
    public function submit()
    {
        $result = 0;

        $test = Test::create([
            'user_id' => auth()->id(),
            'quiz_id' => $this->quiz->id,
            'result' => 0,
            'ip_address' => request()->ip(),
            'time_spent' => now()->timestamp - $this->startTimeInSeconds
        ]);

        // foreach ($this->answersOfQuestions as $key => $optionId) {
        //     if (!empty($optionId) && Option::find($optionId)->correct) {
        //         $result++;
        //         Answer::create([
        //             'user_id' => auth()->id(),
        //             'test_id' => $test->id,
        //             'question_id' => $this->questions[$key]->id,
        //             'option_id' => $optionId,
        //             'correct' => 1
        //         ]);
        //     } else {
        //         Answer::create([
        //             'user_id' => auth()->id(),
        //             'test_id' => $test->id,
        //             'question_id' => $this->questions[$key]->id,
        //         ]);
        //     }
        // }

        foreach ($this->answersOfQuestions as $key => $optionId) {
            // Check if option exists in the database
            $option = !empty($optionId) ? Option::find($optionId) : null;
        
            // Prepare answer data
            $answerData = [
                'user_id' => auth()->id(),
                'test_id' => $test->id,
                'question_id' => $this->questions[$key]->id,
                'correct' => $option && $option->correct ? 1 : 0 // Mark correct if valid, otherwise incorrect
            ];
        
            // Only add option_id if it's valid
            if (!empty($optionId)) {
                $answerData['option_id'] = $optionId;
            }
        
            // Insert answer into the database
            Answer::create($answerData);
        
            // Increment result only if the answer is correct
            if ($option && $option->correct) {
                $result++;
            }
        }
        
        $test->update([
            'result' => $result
        ]);


        return to_route('results.show', ['test' => $test]);
    }

    public function render(): View
    {
        return view('livewire.front.quizzes.show');
    }

    
}
