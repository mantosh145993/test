<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\PDF;
use App\Models\Test;
use App\Models\Answer;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class ResultController extends Controller
{
    public function show(Test $test): View
    {
        $questions_count = $test->quiz->questions->count();

        $marks_count = $test->quiz->questions->count();

        $results = Answer::where('test_id', $test->id)
            ->with('question.options')
            ->get();

        if (!$test->quiz->public) {
            $leaderboard = Test::query()
                ->where('quiz_id', $test->quiz_id)
                ->whereHas('user')
                ->with(['user' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->orderBy('result', 'desc')
                ->orderBy('time_spent')
                ->get();

            return view('front.quizzes.result', compact('test', 'questions_count', 'results', 'leaderboard'));
        }

        return view('front.quizzes.result', compact('test', 'questions_count', 'results'));
    }


    public function downloadPDF(Test $test)
    {
        // Get the total number of questions in the quiz
        $questions_count = $test->quiz->questions->count();
        $currentDateTime = Carbon::now()->format('l, F j, Y h:i A');
        // Get all the answers with the related question options
        $results = Answer::where('test_id', $test->id)
            ->with('question.options')
            ->get();
    
        // Check if the quiz is not public and get leaderboard data
        if (!$test->quiz->public) {
            $leaderboard = Test::query()
                ->where('quiz_id', $test->quiz_id)
                ->whereHas('user')
                ->with(['user' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->orderBy('result', 'desc')
                ->orderBy('time_spent')
                ->get();
        }
    
        // Create an instance of the PDF class
        $pdf = app(PDF::class); // This ensures we're getting the instance of the PDF facade
    
        // Load the view and pass data
        $pdf = $pdf->loadView('pdf.test-results', [
            'test' => $test,
            'questions_count' => $questions_count,
            'results' => $results,
            'leaderboard' => $leaderboard ?? null,
            'currentDateTime' => $currentDateTime,
        ]);
    
        // Return the generated PDF as a download
        return $pdf->download('test-results-' . $test->id . '.pdf');
    }
    
}
