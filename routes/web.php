<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Http\Livewire\Admin\AdminForm;
use App\Http\Livewire\Admin\AdminList;
use App\Http\Livewire\Admin\Tests\TestList;
use App\Http\Livewire\Front\Leaderboard;
use App\Http\Livewire\Front\Results\ResultList;
use App\Http\Livewire\Question\QuestionForm;
use App\Http\Livewire\Question\QuestionList;
use App\Http\Livewire\Quiz\QuizForm;
use App\Http\Livewire\Quiz\QuizList;
use Illuminate\Support\Facades\Route;

// public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::middleware('throttle:1,1')->group(function () {
    Route::get('quiz/{quiz}', [HomeController::class, 'show'])->name('quiz.show');
});
Route::get('results/{test}', [ResultController::class, 'show'])->name('results.show');
Route::get('test/{test}/download-pdf', [ResultController::class, 'downloadPDF'])->name('test.downloadPDF');

// protected routes
Route::middleware('auth')->group(function () {
    Route::get('leaderboard', Leaderboard::class)->name('leaderboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('myresults', ResultList::class)->name('myresults');

    // Admin routes
    Route::middleware('isAdmin')->group(function () {
        Route::get('questions', QuestionList::class)->name('questions');
        Route::get('questions/create', QuestionForm::class)->name('question.create');
        Route::get('questions/{question}', QuestionForm::class)->name('question.edit');
        Route::post('/upload-blog', [QuestionForm::class, 'uploadBlog'])->name('admin.upload-blog');
        Route::get('quizzes', QuizList::class)->name('quizzes');
        Route::get('quizzes/create', QuizForm::class)->name('quiz.create');
        Route::get('quizzes/{quiz}/edit', QuizForm::class)->name('quiz.edit');

        Route::get('admins', AdminList::class)->name('admins');
        Route::get('admins/create', AdminForm::class)->name('admin.create');

        Route::get('tests', TestList::class)->name('tests');
    });
});
require __DIR__ . '/auth.php';
