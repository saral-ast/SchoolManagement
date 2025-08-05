@extends('layouts.app')

@section('content')
    {{-- @dd($teacher->user) --}}
    <x-profileform 
        :user="$parent->user"
        method="put"
        :action="route('parent.update', $parent->id)"
        :cancel="route('parent.index')" 
        name="Edit Parent"
    >
        <x-form-field label='Occupation' name="occupation" type="text" placeholder="Teacher,Farmer" value="{{ old('occupation', $parent->occupation) }}" />
        <x-form-field label='Relation' name="relation" type="text" value="{{ old('relation', $parent->relation) }}" />
        <x-form-field label='Secondary Phone' name="secondary_phone" type="text" value="{{ old('secondary_phone', $parent->secondary_phone) }}" />
        
        {{-- Added Class Selection --}}
        <x-form-field label="Class" name="class_id" type="select" id="class-select">
            <option value="" class="bg-dark text-muted">Select Class</option>
            @foreach ($allClass as $class)
                <option value="{{ $class->id }}" class="bg-dark text-white" 
                    @selected(old('class_id', $parent->student->class_id ?? '') == $class->id)>
                    {{ $class->name }}
                </option>
            @endforeach
        </x-form-field>
        
        {{-- Added Student Selection --}}
        <x-form-field label="Student" name='student_id' type="select" id='student-select'>
            @if(isset($parent->student))
                <option value="{{ $parent->student->id }}" class="bg-dark text-white" selected>
                    {{ $parent->student->user->name }}
                </option>
            @else
                <option value="" class="bg-dark text-muted">First Select a Class</option>
            @endif
        </x-form-field>

    </x-profileform>

@endsection


@push('js')
<script>
$(document).ready(function(){
    // Load students for the currently selected class on page load
    const initialClassId = $('#class-select').val();
    if (initialClassId) {
        loadStudents(initialClassId, {{ $parent->student->id ?? 'null' }});
    }
    
    // Handle class selection change
    $('#class-select').on('change', function(){
        const classId = $(this).val();
        loadStudents(classId);
    });
    
    function loadStudents(classId, selectedStudentId = null) {
        const $studentSelect = $('#student-select');
        
        $studentSelect.html('<option value="" class="bg-dark text-muted">Loading Students ....</option>');
        
        if (classId) {
            $.ajax({
                url: `{{ route('classes.students', ['id' => '__CLASS_ID__']) }}`.replace('__CLASS_ID__', classId),
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $studentSelect.html('<option value="" class="bg-dark text-muted">Select Student</option>');
                    
                    $.each(data.students, function(index, student) {
                        const isSelected = selectedStudentId && student.id == selectedStudentId ? 'selected' : '';
                        $studentSelect.append(
                            `<option value="${student.id}" class="bg-dark text-white" ${isSelected}>${student.user.name}</option>`
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
    }
});
</script>
@endpush
