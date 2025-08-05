@extends('layouts.app')

@section('content')
    {{-- @dd($teacher->user) --}}
    <x-profileform 
        :user="$teacher->user"
        method="put"
        :action="route('teacher.update', $teacher->id)"
        :cancel="route('teacher.index')" 
        name="Edit Teacher"
    >
         <x-form-field label='Qualification' name="qualification" type="text" placeholder="Msc,Bsc,B.tech" value="{{ old('qualification', $teacher->qualification) }}" />
        <x-form-field label='Joining Date' name="joining_date" type="date" value="{{ old('joining_date', $teacher->joining_date) }}" />
        <div class="mb-3">
            <label class="form-label">Subjects</label>
            <div class="mt-2">
                @foreach ($subjects as $subject)
                    <div class="form-check mb-2">
                        <input 
                            type="checkbox" 
                            name="subject_ids[]" 
                            id="subject_{{ $subject->id }}" 
                            value="{{ $subject->id }}" 
                            class="form-check-input"
                            @if(
                                (old('subject_ids') && in_array($subject->id, old('subject_ids'))) ||
                                (!old('subject_ids') && $teacher->subjects->contains($subject->id))
                            ) checked @endif
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
