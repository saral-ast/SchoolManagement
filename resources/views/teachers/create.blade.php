@extends('layouts.app')

@section('content')
<style>
/* Minimal fix for Black Dashboard theme checkbox visibility */
.form-check-input {
    position: static !important;
    margin:  0!important;
    opacity: 1 !important;
    visibility: visible !important;
}
</style>

<x-profileform name="Create Teacher" :action="route('teacher.store')" :cancel="route('teacher.index')" >
    <x-form-field label="Password" name="password" type="password" value="{{ old('password', '') }}" />
    <x-form-field label="Password Confirmation" name="password_confirmation" type="password" value="{{ old('password_confirmation', '') }}" />
    <x-form-field label='Qualification' name="qualification" type="text" placeholder="Msc,Bsc,B.tech" value="{{ old('qualification', '') }}" />
    <x-form-field label='Joining Date' name="joining_date" type="date" value="{{ old('joining_date', '') }}" />
    <div class="mb-3">
        <label class="form-label">Subjects</label>
        <div class="mt-2">
            @foreach ($subjects as $subject)
                <div class="form-check">
                    <input 
                        type="checkbox" 
                        name="subject_ids[]" 
                        id="subject_{{ $subject->id }}" 
                        value="{{ $subject->id }}" 
                        class="form-check-input"
                        @if(old('subject_ids') && in_array($subject->id, old('subject_ids'))) checked @endif
                    >
                    <label class="form-check-label" for="subject_{{ $subject->id }}">
                        {{ $subject->name }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</x-profileform>
@endsection
