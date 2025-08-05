@extends('layouts.app')

@section('content')
    {{-- @dd($teacher->user) --}}
    <x-profileform 
        :user="$student->user"
        method="put"
        :action="route('student.update', $student->id)"
        :cancel="route('student.index')" 
        name="Edit Student"
    >
        <x-form-field label='Admission Number' name="admission_number" type="text" placeholder="" value="{{ old('admission_number', $student->admission_number) }}" />
        <x-form-field label='Roll Number' name="roll_number" type="number" value="{{ old('roll_number', $student->roll_number) }}" />
        <x-form-field label="Class" name="class_id" type="select">
            <option value="" class="bg-dark text-muted">Select Class</option>
            @foreach ($allClass as $class)
                <option value="{{ $class->id }}" class="bg-dark text-white" 
                        @selected(old('class_id', $student->class_id) == $class->id)>
                    {{ $class->name }}
                </option>
            @endforeach
        </x-form-field>

    </x-profileform>

@endsection
