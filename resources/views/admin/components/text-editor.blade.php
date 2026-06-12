@props(['name' => 'content', 'label' => 'Content', 'value' => '', 'required' => false])

@php
    $editorId = 'editor-' . str_replace(['[', ']'], '_', $name);
@endphp

<div class="form-group">
    @if($label)
        <label class="form-label">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
    @endif
    
    <textarea 
        id="{{ $editorId }}" 
        name="{{ $name }}"
        class="tinymce-editor"
        @if($required) aria-required="true" data-required="true" @endif
    >{!! $value !!}</textarea>
</div>

<style>
    .tox-tinymce {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    
    .tox .tox-editor-header {
        background-color: #f8f9fa;
        border-bottom: none;
    }
    
    .tox .tox-toolbar {
        background-color: #f8f9fa;
    }
</style>

@once
<script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
@endonce
