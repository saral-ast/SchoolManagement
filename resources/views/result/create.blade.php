@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header bg-transparent">
                            <h3 class="mb-0 text-white">Student Exam Details Form</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('result.store') }}">
                                @csrf
                                <!-- Student Selection Section -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <x-form-field
                                            type="select"
                                            name="class_id"
                                            label="Select Class"
                                            class="class-select"
                                            id="class-select">
                                            <option value="" selected class="bg-dark text-white">Select Class</option>
                                            @foreach ($allClass as $class)
                                                <option value="{{ $class->id }}" class="bg-dark text-white">{{ $class->name }}</option>
                                            @endforeach
                                        </x-form-field>
                                    </div>
                                    <div class="col-md-6">
                                        <x-form-field
                                            type="select"
                                            name="student_id"
                                            label="Select Student"
                                            class="student-select"
                                            id="student-select">
                                            <option value="" class="bg-dark text-white" selected>Select Student</option>
                                        </x-form-field>
                                    </div>
                                </div>

                                <!-- Subject Marks Table -->
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="select-all-subjects" />
                                                    <label for="select-all-subjects" style="font-weight:normal;">All</label>
                                                </th>
                                                <th class="text-white">Subject</th>
                                                <th class="text-white">Total Marks</th>
                                                <th class="text-white">Obtained Marks</th>
                                                <th class="text-white">Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allSubject as $subject)
                                            <tr data-subject-id="{{ $subject->id }}">
                                                <td>
                                                    <input
                                                        type="checkbox"
                                                        class="subject-checkbox"
                                                        name="subjects[{{ $subject->id }}]"
                                                        value="1" />
                                                </td>
                                                <td class="fw-semibold text-white">{{ $subject->name }}</td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        class="form-control total-mark"
                                                        name="marks[{{ $subject->id }}][total]"
                                                        value="100"
                                                        min="0"
                                                        disabled />
                                                </td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        class="form-control obtained-mark"
                                                        name="marks[{{ $subject->id }}][obtained]"
                                                        placeholder="Enter marks"
                                                        min="0" max="100"
                                                        disabled />
                                                </td>
                                                <td>
                                                    <input 
                                                        type="text" 
                                                        name="marks[{{ $subject->id }}][grade]" 
                                                        class="form-control grade-input text-white fw-bold" 
                                                        value="-" 
                                                        readonly 
                                                        disabled />
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Exam Summary Section -->
                                <div class="row mb-4">
                                    <div class="col-md-2">
                                        <x-form-field
                                            type="text"
                                            name="total_marks"
                                            label="Total Marks"
                                            value="0"
                                            readonly
                                            id="summary-total-marks" />
                                    </div>
                                    <div class="col-md-2">
                                        <x-form-field
                                            type="text"
                                            name="obtained_marks"
                                            label="Obtained Marks"
                                            value="0"
                                            readonly
                                            id="summary-obtained-marks" />
                                    </div>
                                    <div class="col-md-2">
                                        <x-form-field
                                            type="text"
                                            name="overall_grade"
                                            label="Overall Grade"
                                            value="-"
                                            readonly
                                            id="summary-overall-grade" />
                                    </div>
                                    <div class="col-md-2">
                                        <x-form-field 
                                            type="date" 
                                            name="exam_date" 
                                            label="Exam Date" 
                                            value="{{ old('exam_date') }}" />
                                    </div>
                                    <div class="col-md-2">
                                        <x-form-field
                                            type="select"
                                            name="exam_type"
                                            label="Exam Type">
                                            <option value="" selected class="bg-dark text-white">Select Exam Type</option>
                                            <option value="final" class="bg-dark text-white">Final Exam</option>
                                            <option value="mid_term" class="bg-dark text-white">Midterm Exam</option>
                                            <option value="test" class="bg-dark text-white">Test</option>
                                        </x-form-field>
                                    </div>
                                    <div class="col-md-2">
                                        <x-form-field
                                            type="select"
                                            name="result_status"
                                            label="Result Status">
                                            <option value="" selected class="bg-dark text-white">Select Status</option>
                                            <option value="pass" class="bg-dark text-white">Pass</option>
                                            <option value="fail" class="bg-dark text-white">Fail</option>
                                        </x-form-field>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fas fa-save"></i> Save Exam Details
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-body -->
                    </div><!-- card shadow -->
                </div><!-- col -->
            </div><!-- row -->
        </div><!-- header-body -->
    </div><!-- container-fluid -->
</div><!-- header -->
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Enable/disable marks and grade inputs for each subject row based on checkbox
    $('.subject-checkbox').on('change', function() {
        const $row = $(this).closest('tr');
        const checked = this.checked;
        
        $row.find('input.total-mark').prop('disabled', !checked);
        $row.find('input.obtained-mark').prop('disabled', !checked);
        $row.find('input.grade-input').prop('disabled', !checked);

        if (!checked) {
            $row.find('.obtained-mark').val('');
            $row.find('.grade-input').val('-');
        }

        calculateSummary();
    });

    // Initialize disabled state on page load
    $('.subject-checkbox').each(function() {
        const $row = $(this).closest('tr');
        const checked = this.checked;
        
        $row.find('input.total-mark').prop('disabled', !checked);
        $row.find('input.obtained-mark').prop('disabled', !checked);
        $row.find('input.grade-input').prop('disabled', !checked);

        if (!checked) {
            $row.find('.grade-input').val('-');
        }
    });

    // Select All toggle
    $('#select-all-subjects').on('change', function() {
        const checked = this.checked;
        $('.subject-checkbox').prop('checked', checked).trigger('change');
    });

    // Calculate grade when obtained marks change
    $('.obtained-mark').on('input', function() {
        const $row = $(this).closest('tr');
        const obtained = parseFloat($(this).val()) || 0;
        const total = parseFloat($row.find('.total-mark').val()) || 0;

        const grade = calculateGrade(obtained, total);
        $row.find('.grade-input').val(grade);

        calculateSummary();
    });

    // Calculate grade when total marks change
    $('.total-mark').on('input', function() {
        const $row = $(this).closest('tr');
        const obtained = parseFloat($row.find('.obtained-mark').val()) || 0;
        const total = parseFloat($(this).val()) || 0;

        const grade = calculateGrade(obtained, total);
        $row.find('.grade-input').val(grade);

        calculateSummary();
    });

    function calculateGrade(obtained, total) {
        if (total === 0) return '-';
        const percentage = (obtained / total) * 100;

        if (percentage >= 80) return 'A';
        if (percentage >= 70) return 'B';
        if (percentage >= 60) return 'C';
        if (percentage >= 50) return 'D';
        if (percentage >= 40) return 'E';
        return 'F';
    }

    function calculateSummary() {
        let totalMarks = 0;
        let obtainedMarks = 0;

        $('tbody tr').each(function() {
            const $row = $(this);
            const checked = $row.find('.subject-checkbox').is(':checked');
            if (!checked) return;

            const total = parseFloat($row.find('.total-mark').val()) || 0;
            const obtained = parseFloat($row.find('.obtained-mark').val()) || 0;

            totalMarks += total;
            obtainedMarks += obtained;
        });

        $('#summary-total-marks').val(totalMarks);
        $('#summary-obtained-marks').val(obtainedMarks);

        const overallGrade = calculateGrade(obtainedMarks, totalMarks);
        $('#summary-overall-grade').val(overallGrade);
    }

    // AJAX for student select
    $('#class-select').on('change', function() {
        const classId = $(this).val();
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
                        $studentSelect.append(
                            `<option value="${student.id}" class="bg-dark text-white">${student.user.name}</option>`
                        );
                    });
                },
                error: function() {
                    $studentSelect.html('<option value="" class="bg-dark text-muted">Error loading students</option>');
                }
            });
        } else {
            $studentSelect.html('<option value="" class="bg-dark text-muted">First select a class</option>');
        }
    });
     $('form').on('submit', function(e) {
        let valid = true;
        let message = '';

        // Check class selected
        if ($('#class-select').val() === "") {
            valid = false;
            message += 'Please select a Class.\n';
        }

        // Check student selected
        if ($('#student-select').val() === "") {
            valid = false;
            message += 'Please select a Student.\n';
        }

        // Check exam date selected
        if ($('input[name="exam_date"]').val() === "") {
            valid = false;
            message += 'Please select the Exam Date.\n';
        }

        // Check at least one subject selected
        if ($('.subject-checkbox:checked').length === 0) {
            valid = false;
            message += 'Please select at least one Subject.\n';
        }

        // Validate each selected subject's marks
        $('.subject-checkbox:checked').each(function() {
            const $row = $(this).closest('tr');
            const total = parseFloat($row.find('input.total-mark').val());
            const obtained = parseFloat($row.find('input.obtained-mark').val());

            if (isNaN(total) || total <= 0) {
                valid = false;
                message += 'Total Marks must be a positive number for selected subjects.\n';
                return false; // break .each loop
            }
            if (isNaN(obtained) || obtained < 0 || obtained > total) {
                valid = false;
                message += 'Obtained Marks must be between 0 and Total Marks for selected subjects.\n';
                return false;
            }
        });

        if (!valid) {
            alert(message);
            e.preventDefault();
        }
    });
});
</script>
@endpush
