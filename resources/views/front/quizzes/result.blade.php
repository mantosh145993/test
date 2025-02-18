<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <img src="{{ asset('logo/logo.png') }}" class="w-48 h-auto sm:w-52 mt-3" alt="Logo">
                    <div class="flex flex-wrap justify-between items-center">
                        <h1 class="text-xl font-bold text-center w-full sm:w-auto mt-3">Test Results ({{ $test->quiz?->title ?? 'No Quiz Available' }})</h1>
                    </div>
                    <!-- Download Button -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('test.downloadPDF', $test->id) }}" class="custom-btn">
                            Download Answer Key
                        </a>
                    </div>

                    <!-- Responsive Table -->
                    <div class="mt-4 overflow-x-auto">
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <tbody class="bg-white">

                                @if (auth()->user()?->is_admin)
                                <tr>
                                    <th class="border bg-gray-100 px-4 py-2 text-left text-sm font-semibold uppercase">User</th>
                                    <td class="border px-4 py-2">{{ $test->user->name ?? '' }} ({{ $test->user->email ?? '' }})</td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="border bg-gray-100 px-4 py-2 text-left text-sm font-semibold uppercase">Date</th>
                                    <td class="border px-4 py-2">{{ $test->created_at?->format('D d/m/Y, h:i A') ?? '' }}</td>
                                </tr>

                                @php
                                    $obtainedMarks = 0;
                                    $totalMarks = 0;

                                    foreach ($results as $result) {
                                        $totalMarks += $result->question->marks;
                                    }

                                    $obtainedMarks = $test->result * ($totalMarks / max(1, count($results))); // Prevent division by zero
                                    $percentage = ($totalMarks > 0) ? ($obtainedMarks / $totalMarks) * 100 : 0;
                                    $totalQuestions = count($results);
                                    $attemptedQuestions = $results->whereNotNull('option_id')->count();
                                    $correctAnswers = $results->where('correct', 1)->count();
                                @endphp

                                <tr>
                                    <th class="border bg-gray-100 px-4 py-2 text-left text-sm font-semibold uppercase">Obtained Marks</th>
                                    <td class="border px-4 py-2">{{ $obtainedMarks }}</td>
                                </tr>

                                <tr>
                                    <th class="border bg-gray-100 px-4 py-2 text-left text-sm font-semibold uppercase">Total Marks</th>
                                    <td class="border px-4 py-2">{{ $totalMarks }}</td>
                                </tr>

                                <tr>
                                    <th class="border bg-gray-100 px-4 py-2 text-left text-sm font-semibold uppercase">Percentage</th>
                                    <td class="border px-4 py-2">{{ number_format($percentage, 2) }}%</td>
                                </tr>
                                <tr>
                                    <th class="border bg-gray-100 px-4 py-2 text-left text-sm font-semibold uppercase">Attempted Question</th>
                                    <td class="border px-4 py-2">{{ $attemptedQuestions }}</td>
                                </tr>
                                <tr>
                                    <th class="border bg-gray-100 px-4 py-2 text-left text-sm font-semibold uppercase">Correct Question</th>
                                    <td class="border px-4 py-2">{{ $correctAnswers }}</td>
                                </tr>

                                <tr>
                                    <th class="border bg-gray-100 px-4 py-2 text-left text-sm font-semibold uppercase">Correct result from total question within time</th>
                                    <td class="border px-4 py-2">
                                        {{ $test->result }} / {{ $questions_count }}
                                        @if ($test->time_spent)
                                            ({{ sprintf('%.2f', $test->time_spent / 60) }} minutes)
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @isset($leaderboard)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h6 class="text-xl font-bold">Leaderboard</h6>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full mt-4 border-collapse border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-left">Rank</th>
                                    <th class="border px-4 py-2 text-left">Username</th>
                                    <th class="border px-4 py-2 text-left">Results</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaderboard as $test)
                                <tr class="@if(auth()->user()->name == $test->user->name) bg-gray-100 @endif">
                                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="border px-4 py-2">{{ $test->user->name }}</td>
                                    <td class="border px-4 py-2">
                                        {{ $test->result }} / {{ $questions_count }}
                                        ({{ sprintf('%.2f', $test->time_spent / 60) }} minutes)
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endisset

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @foreach ($results as $result)
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full my-4 border-collapse border border-gray-300">
                            <tbody>
                                <tr class="bg-gray-100">
                                    <td class="border px-4 py-2">Question #{{ $loop->iteration }}</td>
                                    <td class="border px-4 py-2">{!! nl2br($result->question->text) !!}</td>
                                </tr>
                                <tr>
                                    <td class="border px-4 py-2">Options</td>
                                    <td class="border px-4 py-2">
                                        @foreach ($result->question->options as $index => $option)
                                            @if ($index === 0 && $result->question->file)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $result->question->file) }}" alt="Option Image" class="w-full h-auto">
                                            </div>
                                            @endif

                                            <li @class(['underline' => $result->option_id == $option->id, 'font-bold' => $option->correct == 1])>
                                                {!! strip_tags($option->text, '<span>') !!}
                                                @if ($option->correct == 1)
                                                <span class="italic">(correct answer)</span>
                                                @endif
                                                @if ($result->option_id == $option->id)
                                                <span class="italic">(your answer)</span>
                                                @endif
                                            </li>
                                        @endforeach

                                        @if (is_null($result->option_id))
                                            <span class="font-bold italic">Question unanswered.</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if (!$loop->last)
                    <hr class="my-4">
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .custom-btn {
        background-color:rgb(235, 30, 30);
        color: white;
        padding: 10px 15px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 15px;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .custom-btn:hover {
        background-color:rgb(142, 4, 4);
        color: white;
    }

    .custom-btn:focus {
        outline: none;
        box-shadow: 0 0 5px #4CAF50;
    }
</style>