@extends('layouts.app')

@section('content')
<x-profileform name="Create Studnet" :action="route('student.store')" :cancel="route('student.index')" >
        <x-form-field label="Password" name="password" type="password" value="{{ old('password', '') }}" />
        <x-form-field label="Password Confirmation" name="password_confirmation" type="password" value="{{ old('password_confirmation', '') }}" />
        <x-form-field label='Admission Number' name="admission_number" type="text" placeholder="" value="{{ old('admission_number', '') }}" />
        <x-form-field label='Roll Number' name="roll_number" type="number" value="{{ old('roll_number', '') }}" />
       <x-form-field  label="Class" name="class_id"  type="select">
            <option value="" class="bg-dark text-muted">Select Class</option>
            @foreach ($allClass as $class)
                <option value="{{ $class->id }}" class="bg-dark text-white" @selected(old('class_id') == $class->id)>
                    {{ $class->name }}
                </option>
            @endforeach
        </x-form-field>
</x-profileform>
@endsection
