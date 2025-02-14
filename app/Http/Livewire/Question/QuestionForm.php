<?php

namespace App\Http\Livewire\Question;

use App\Models\Question;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

// class QuestionForm extends Component
// {
//     use WithFileUploads;
//     public Question $question;
//     public array $options = [];
//     public $file;
//     public bool $editing = false;

//     protected $rules = [
//         'question.text' => 'required|string',
//         'file' => 'nullable|file|max:2048',
//         'question.code_snippet' => 'nullable|string',
//         'question.answer_explanation' => 'nullable|string',
//         'question.more_info_link' => 'nullable|url',
//         'options' => 'required|array',
//         'options.*.text' => 'required|string',
//     ];

//     public function save()
//     {
//         $this->validate();

//        // Handle file upload properly
//        if ($this->file) {
//             $uniqueName = Str::uuid() . '.' . $this->file->getClientOriginalExtension();
//             $path = $this->file->storeAs('questions', $uniqueName, 'public');
//             $this->question->file = $path;
//         }
//         $this->question->save();

//         $this->question->options()->delete();

//         foreach ($this->options as $option) {
//             $this->question->options()->create($option);
//         }

//         return to_route('questions');
//     }

    // public function mount(Question $question): Void
    // {
    //     $this->question = $question;

    //     if ($this->question->exists) {
    //         $this->editing = true;

    //         foreach ($this->question->options as $option) {
    //             $this->options[] = [
    //                 'id' => $option->id,
    //                 'text' => $option->text,
    //                 'correct' => $option->correct,
    //             ];
    //         }
    //     }
    // }

//     public function addOption(): Void
//     {
//         $this->options[] = [
//             'text' => '',
//             'correct' => false
//         ];
//     }

//     public function removeOption(int $index): Void
//     {
//         unset($this->options[$index]);
//         $this->options = array_values(($this->options));
//     }

//     public function render(): View
//     {
//         return view('livewire.question.question-form');
//     }
// }

class QuestionForm extends Component
{
    use WithFileUploads;

    public Question $question;
    public array $options = [];
    public $file;
    public bool $editing = false;


    protected $listeners = ['updateQuestionText', 'updateOptionText','saveForm'];



    protected $rules = [
        'question.text' => 'required|string',
        'file' => 'nullable|file|max:2048',
        'question.answer_explanation' => 'nullable|string',
        'options' => 'required|array',
        'options.*.text' => 'required|string',
        'question.marks' => 'required',
    ];

    public function updateQuestionText($content)
    {
        $this->question->text = $content;
    }

    public function updateOptionText($index, $content)
    {
        // $this->options[$index]['text'] = $content;
        if (isset($this->options[$index])) {
            $this->options[$index]['text'] = $content;
        }
    }
    
    public function save()
    {
        $this->validate();

        if ($this->file) {
            $uniqueName = Str::uuid() . '.' . $this->file->getClientOriginalExtension();
            $path = $this->file->storeAs('questions', $uniqueName, 'public');
            $this->question->file = $path;
        }

        $this->question->save();
        $this->question->options()->delete();

        foreach ($this->options as $option) {
            $this->question->options()->create($option);
        }

        return to_route('questions');
    }

    public function mount(Question $question): Void
    {
        $this->question = $question;

        if ($this->question->exists) {
            $this->editing = true;

            foreach ($this->question->options as $option) {
                $this->options[] = [
                    'id' => $option->id,
                    'text' => $option->text,
                    'correct' => $option->correct,
                ];
            }
            $this->emit('reinitializeFroala');
        }
    }

    public function addOption(): Void
    {
        $this->options[] = [
            'text' => '',
            'correct' => false
        ];

        $this->emit('reinitializeFroala'); // Ensures Froala editor is reinitialized
    }

    public function removeOption(int $index): Void
    {
        unset($this->options[$index]);
        $this->options = array_values(($this->options));
    }

    public function render(): View
    {
        return view('livewire.question.question-form');
    }

    public function saveForm()
    {
        $this->save();
    }

    public function uploadBlog(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Validate image (max 5MB)
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);

            return response()->json([
                'link' => asset('uploads/' . $filename) // Return uploaded file's URL
            ]);
        }

        return response()->json(['error' => 'File upload failed.'], 400);
    }
}