<!-- resources/views/livewire/trix-editor.blade.php -->
<div>
    <input id="text" type="hidden" wire:model="content">
    <trix-editor input="text"></trix-editor>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
@endpush
