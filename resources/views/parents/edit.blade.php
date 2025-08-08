@extends('layouts.app')

@section('content')
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
        
        <!-- Multiple Class Selection with Select2 -->
        <div class="form-group">
            <label for="class-select">Select Classes <span class="text-danger">*</span></label>
            <select name="class_ids[]" id="class-select" class="form-control select2-multiple" multiple required>
                @foreach ($allClass as $class)
                    <option value="{{ $class->id }}" 
                        @if(old('class_ids') && in_array($class->id, old('class_ids'))) selected @endif>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Select one or more classes</small>
        </div>

        <!-- Multiple Student Selection with Select2 -->
        <div class="form-group">
            <label for="student-select">Select Students <span class="text-danger">*</span></label>
            <select name="student_id[]" id="student-select" class="form-control select2-multiple" multiple required>
                <option value="" disabled>First select classes to load students</option>
            </select>
            <small class="form-text text-muted">Students will load based on selected classes</small>
        </div>
    </x-profileform>
@endsection

@push('js')
<script>
$(document).ready(function(){
    // Initialize Select2 for class selection
    $('#class-select').select2({
        placeholder: 'Select classes...',
        allowClear: true,
        theme: 'bootstrap-5',
        width: '100%',
        closeOnSelect: false
    });

    // Initialize Select2 for student selection
    $('#student-select').select2({
        placeholder: 'Select students...',
        allowClear: true,
        theme: 'bootstrap-5',
        width: '100%',
        closeOnSelect: false
    });

    // Initialize with existing data
    const existingStudents = @json($parent->students->pluck('id')->toArray());
    const existingClasses = @json($parent->students->pluck('class_id')->unique()->toArray());
    
    // Set existing classes as selected
    existingClasses.forEach(classId => {
        $(`#class-select option[value="${classId}"]`).prop('selected', true);
    });
    
    // Trigger change to update Select2 display
    $('#class-select').trigger('change');
    
    // Load students for existing classes
    if (existingClasses.length > 0) {
        loadStudentsForClasses(existingClasses, existingStudents);
    }
    
    // Handle class selection change
    $('#class-select').on('change', function(){
        const selectedClasses = $(this).val() || [];
        const $studentSelect = $('#student-select');

        // Clear student selection
        $studentSelect.empty().trigger('change');

        if (selectedClasses.length > 0) {
            // Remove empty values
            const validClasses = selectedClasses.filter(id => id !== '');
            
            if (validClasses.length > 0) {
                loadStudentsForClasses(validClasses);
            }
        } else {
            const option = new Option('First select classes to load students', '', false, false);
            option.disabled = true;
            $studentSelect.append(option).trigger('change');
        }
    });

    function loadStudentsForClasses(classIds, selectedStudentIds = []) {
        const $studentSelect = $('#student-select');
        
        $.ajax({
            url: '{{ route("classes.students.multiple") }}',
            type: 'POST',
            dataType: 'json',
            data: {
                class_ids: classIds,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.students && data.students.length > 0) {
                    // Group students by class for better display
                    const studentsByClass = {};
                    data.students.forEach(student => {
                        const className = student.class.name;
                        if (!studentsByClass[className]) {
                            studentsByClass[className] = [];
                        }
                        studentsByClass[className].push(student);
                    });

                    // Add students grouped by class
                    Object.keys(studentsByClass).forEach(className => {
                        studentsByClass[className].forEach(student => {
                            const isSelected = selectedStudentIds.includes(student.id) || 
                                             @json(old('student_id', [])).includes(student.id.toString());
                            const option = new Option(
                                `${student.user.name} (${className})`, 
                                student.id, 
                                isSelected, 
                                isSelected
                            );
                            $studentSelect.append(option);
                        });
                    });
                } else {
                    const option = new Option('No students found in selected classes', '', false, false);
                    option.disabled = true;
                    $studentSelect.append(option);
                }
                
                $studentSelect.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching students:', error);
                const option = new Option('Error loading students', '', false, false);
                option.disabled = true;
                $studentSelect.append(option).trigger('change');
            }
        });
    }
});
</script>

<style>
/* Custom Select2 styling for dark theme */
.select2-container--bootstrap-5 .select2-selection--multiple {
    background-color: #27293d;
    border: 1px solid #344675;
    border-radius: 0.375rem;
    color: #ffffff;
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
    background-color: #17a2b8;
    border: 1px solid #138496;
    border-radius: 0.25rem;
    color: white;
    padding: 2px 8px;
    margin: 2px;
    font-size: 0.875rem;
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
    color: white;
    margin-right: 5px;
    font-weight: bold;
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #ffc107;
}

.select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
    background-color: #17a2b8;
    color: white;
}

.select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
    background-color: #344675;
    color: #ffffff;
}

.select2-container--bootstrap-5 .select2-dropdown {
    background-color: #27293d;
    border: 1px solid #344675;
}

.select2-container--bootstrap-5 .select2-results__option {
    color: #ffffff;
}

.select2-container--bootstrap-5 .select2-search__field {
    background-color: #27293d;
    color: #ffffff;
    border: 1px solid #344675;
}

.select2-container--bootstrap-5 .select2-selection__placeholder {
    color: #6c757d;
}
</style>
@endpush
