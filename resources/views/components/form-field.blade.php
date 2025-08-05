@props([
    'label',
    'name',
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
])

<div class="form-group{{ $errors->has($name) ? ' has-danger' : '' }}">
   
    @if($type === 'select')
         <label>{{ $label }}</label>
        <select name="{{ $name }}" {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
            {{ $slot }}
        </select>
    @elseif ($type === 'checkbox')
        <div class="checkbox-group">
            <label>{{ $label }}</label>
            <div class="checkbox-options">
                {{ $slot }}
                
            </div>
        </div>
    @else
         <label>{{ $label }}</label>
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
        />
    @endif
    {{-- @dd($errors) --}}
    {{-- @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror --}}
    @include('alerts.feedback', ['field' => $name])
</div>
