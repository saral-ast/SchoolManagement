@props([
    'name',
    'value',
    'label',
    'checked' => false,
    'id' => null,
])

@php
    $checkboxId = $id ?: $name . '_' . $value;
@endphp

<div class="form-check mb-2">
    <input 
        type="checkbox" 
        name="{{ $name }}" 
        id="{{ $checkboxId }}" 
        value="{{ $value }}" 
        {{ $attributes->merge(['class' => 'form-check-input']) }}
        @if(old($name) && in_array($value, (array)old($name)) || $checked) checked @endif
    >
    <label class="form-check-label" for="{{ $checkboxId }}">
        {{ $label }}
    </label>
</div>
