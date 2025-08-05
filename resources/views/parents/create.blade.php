@extends('layouts.app')

@section('content')
<x-profileform name="Create Parents" :action="route('parent.store')" :cancel="route('parent.index')" >
    <x-form-field label="Password" name="password" type="password" value="{{ old('password', '') }}" />
    <x-form-field label="Password Confirmation" name="password_confirmation" type="password" value="{{ old('password_confirmation', '') }}" />
    <x-form-field label='Occupation' name="occupation" type="text" placeholder="Teacher,Farmer" value="{{ old('occupation', '') }}" />
    <x-form-field label='Relation' name="relation" type="text" value="{{ old('relation', '') }}" />
    <x-form-field label='Secondary Phone' name="secondary_phone" type="text" value="{{ old('secondary_phone', '') }}" />
    
    <x-form-field label="Class" name="class_id" type="select" id="class-select">
        <option value="" class="bg-dark text-muted">Select Class</option>
        @foreach ($allClass as $class)
            <option value="{{ $class->id }}" class="bg-dark text-white" @selected(old('class_id') == $class->id)>
                {{ $class->name }}
            </option>
        @endforeach
    </x-form-field>
    <x-form-field label="Student" name='student_id' type="select" id='student-select'>
        <option value="" class="bg-dark text-muted">First Select a Class</option>
    </x-form-field>

    
</x-profileform>


@endsection
@push('js')
<script>
$(document).ready(function(){
    $('#class-select').on('change',function(){
        const classId = $(this).val();
        const $studentSelect = $('#student-select');

        $studentSelect.html('<option value = "" class="bg-dark text-muted>Loading Students ....</option>')

        if (classId) {
            $.ajax({
                url: `{{ route('classes.students', ['id' => '__CLASS_ID__']) }}`.replace('__CLASS_ID__', classId),

                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token
                },
                success: function(data) {
                    // console.log(data)
                    $studentSelect.html('<option value="" class="bg-dark text-muted">Select Student</option>');
                    
                    $.each(data.students, function(index, student) {
                        $studentSelect.append(
                            `<option value="${student.id}" class="bg-dark text-white">${student.user.name}</option>`
                        );
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching students:', error);
                    $studentSelect.html('<option value="" class="bg-dark text-muted">Error loading students</option>');
                }
            });
        } else {
            $studentSelect.html('<option value="" class="bg-dark text-muted">First select a class</option>');
        }
    })
})
</script>
@endpush