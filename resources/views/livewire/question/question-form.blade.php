<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $editing ? 'Edit Question' : 'Create Question' }}
        </h2>
    </x-slot>

    <x-slot name="title">
        {{ $editing ? 'Edit Question ' . $question->id : 'Create Question' }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit.prevent="save" enctype="multipart/form-data">
                        <!-- <div>
                            <x-input-label for="text" value="Question text" />
                            <x-textarea wire:model.defer="question.text" id="text" class="block mt-1 w-full"
                                type="text" name="text" required />
                            <x-input-error :messages="$errors->get('question.text')" class="mt-2" />
                        </div> -->
                        <div wire:ignore>
                            <x-input-label for="text" value="Question text" />
                            <textarea wire:model.defer="question.text" id="text" class="block mt-1 w-full" type="text" name="text"></textarea>
                        </div>
                        <x-input-error :messages="$errors->get('question.text')" class="mt-2" />

                        <div>
                            <x-input-label for="file" value="Upload File" />
                            <input type="file" wire:model="file" id="file" class="block mt-1 w-full">
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                            @if ($question->file)
                            <div class="mt-2">
                                <p>Current file:</p>
                                <img src="{{ asset('storage/' . $question->file) }}" alt="Question Image" width="300" height="200">
                            </div>
                            @else
                            <p>No image preview available</p>
                            @endif
                        </div>
                        <!-- <div class="mt-4">
                            <x-input-label for="options" value="Question options" />
                            @foreach ($options as $index => $option)
                                <div class="flex mt-2">
                                    <x-text-input type="text" wire:model.defer="options.{{ $index }}.text"
                                        class="w-full" name="options_{{ $index }}"
                                        id="options_{{ $index }}" autocomplete="off" />
                                    <div class="flex items-center">
                                        <input type="checkbox" class="mr-1 ml-4"
                                            wire:model.defer="options.{{ $index }}.correct"> Correct
                                        <button wire:click="removeOption({{ $index }})" type="button"
                                            class="ml-4 rounded-md border border-transparent bg-red-200 px-4 py-2 text-xs uppercase text-red-500 hover:bg-red-300 hover:text-red-700">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('options.' . $index . '.text')" class="mt-2" />
                            @endforeach

                            <x-input-error :messages="$errors->get('options')" class="mt-2" />

                            <x-primary-button wire:click="addOption" type="button" class="mt-2">
                                Add
                            </x-primary-button>
                        </div> -->
                        <!-- Question Options -->
                        <div class="mt-4">
                            <x-input-label for="options" value="Question options" />

                            @foreach ($options as $index => $option)
                            <div class="flex mt-2">
                                <!-- Froala Editor for Each Option -->
                                <div class="w-full" wire:ignore>
                                    <textarea id="option_{{ $index }}" class="block w-full"></textarea>
                                    {!! old('options.' . $index . '.text', $option['text'] ?? '') !!}
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" class="mr-1 ml-4"
                                        wire:model.defer="options.{{ $index }}.correct"> Correct
                                    <button wire:click="removeOption({{ $index }})" type="button"
                                        class="ml-4 rounded-md border border-transparent bg-red-200 px-4 py-2 text-xs uppercase text-red-500 hover:bg-red-300 hover:text-red-700">
                                        Delete
                                    </button>
                                </div>
                            </div>

                            <x-input-error :messages="$errors->get('options.' . $index . '.text')" class="mt-2" />
                            @endforeach
                        </div>

                        <!-- Add Option Button -->
                        <x-primary-button wire:click="addOption" type="button" class="mt-2">
                            Add Option
                        </x-primary-button>
               
                <div class="mt-4">
                    <x-input-label for="code_snippet" value="Code snippet" />
                    <x-textarea wire:model.defer="question.code_snippet" id="code_snippet"
                        class="block mt-1 w-full" type="text" name="code_snippet" />
                    <x-input-error :messages="$errors->get('question.code_snippet')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="answer_explanation" value="Answer explanation" />
                    <x-textarea wire:model.defer="question.answer_explanation" id="answer_explanation"
                        class="block mt-1 w-full" type="text" name="answer_explanation" />
                    <x-input-error :messages="$errors->get('question.answer_explanation')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="more_info_link" value="More info link" />
                    <x-text-input wire:model.defer="question.more_info_link" id="more_info_link"
                        class="block mt-1 w-full" type="text" name="more_info_link" />
                    <x-input-error :messages="$errors->get('question.more_info_link')" class="mt-2" />
                </div>

                <!-- <div class="mt-4">
                    <x-primary-button>
                        Save
                    </x-primary-button>
                </div> -->
                <div class="mt-4">
                <x-primary-button wire:click="$emit('beforeSave')">
                    Save
                </x-primary-button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<style>
    .fr-second-toolbar {
    display: none !important;
}

</style>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@4.0.7/js/froala_editor.pkgd.min.js"></script>
<!-- Initialize Froala Editor -->
<script>
    document.addEventListener('livewire:load', function() {
        new FroalaEditor('#text', {
            imageUploadURL: "{{ route('admin.upload-blog') }}", // No need for the CSRF token in the URL
            imageUploadParam: 'file', // Custom param for the uploaded file (optional, if your server expects a specific name)
            imageMaxSize: 5 * 1024 * 1024, // 5MB Max file size
            imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif'], // Allowed image types
            toolbarButtons: [
                'bold', 'italic', 'underline', 'strikeThrough',
                'formatOL', 'formatUL', // Ordered and unordered lists
                'paragraphFormat', // Add heading tags
                'insertLink', // Add hyperlink with text
                'textColor', 'backgroundColor', // Text and background color
                'insertTable', 'insertImage', // Tables and images
                'html', // ✅ Added HTML button
                'undo', 'redo' // Undo and redo actions
            ],
            htmlExecuteScripts: true, // ✅ Allow execution of embedded scripts
            codeMirror: true, // ✅ Enables syntax highlighting in the HTML view
            codeMirrorOptions: { // ✅ Custom options for better code editing experience
                mode: "text/html",
                tabMode: "indent",
                lineNumbers: true
            },
            paragraphFormat: { // Define paragraph formats (headings and normal text)
                N: 'Normal',
                H1: 'Heading 1',
                H2: 'Heading 2',
                H3: 'Heading 3',
                H4: 'Heading 4',
                H5: 'Heading 5',
                H6: 'Heading 6'
            },
            linkInsertButtons: ['linkBack'], // Enables the option to add text on hyperlinks
            colorsBackground: [ // Customize background colors
                '#FFFFFF', '#FF0000', '#00FF00', '#0000FF', '#FFFF00'
            ],
            colorsText: [ // Customize text colors
                '#000000', '#FF0000', '#00FF00', '#0000FF', '#FFFFFF'
            ],
            requestHeaders: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Automatically include CSRF token in the headers
            }
        });
    });
</script>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let questionEditor = new FroalaEditor('#text', {
            events: {
                'contentChanged': function () {
                    let content = this.html.get();
                    Livewire.emit('updateQuestionText', content);
                }
            }
        });

        function initializeFroalaEditors() {
            document.querySelectorAll('[id^="option_"]').forEach((textarea) => {
                let index = textarea.id.split('_')[1]; // Extract index from ID
                if (!textarea.froalaEditor) {
                    new FroalaEditor(`#${textarea.id}`, {
                        events: {
                            'contentChanged': function () {
                                let content = this.html.get();
                                Livewire.emit('updateOptionText', index, content);
                            }
                        }
                    });
                }
            });
        }

        initializeFroalaEditors();

        Livewire.on('reinitializeFroala', () => {
            initializeFroalaEditors();
        });

        // Before form submission, update Livewire state
        Livewire.on('beforeSave', () => {
            let questionContent = questionEditor.html.get();
            Livewire.emit('updateQuestionText', questionContent);

            document.querySelectorAll('[id^="option_"]').forEach((textarea) => {
                let index = textarea.id.split('_')[1];
                let optionEditor = FroalaEditor.INSTANCES.find(editor => editor.el.id === textarea.id);
                if (optionEditor) {
                    let content = optionEditor.html.get();
                    Livewire.emit('updateOptionText', index, content);
                }
            });

            Livewire.emit('saveForm'); // Now trigger save in Livewire
        });
    });
</script>
@endpush
