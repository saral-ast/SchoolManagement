@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header bg-transparent">
                            <h3 class="mb-0 text-white">Edit Student Exam Details</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('result.update', $result->id) }}">
                                @csrf
                                @method('PUT')
                                
                                <!-- Student Information Display (Read-only) -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <x-form-field
                                            type="text"
                                            name="class_name"
                                            label="Class"
                                            value="{{ $result->class->name }}"
                                            readonly />
                                    </div>
                                    <div class="col-md-4">
                                        <x-form-field
                                            type="text"
                                            name="student_name"
                                            label="Student"
                                            value="{{ $result->student->user->name }}"
                                            readonly />
                                    </div>
                                    <div class="col-md-4">
                                        <x-form-field
                                            type="text"
                                            name="exam_date"
                                            label="Current Exam Date"
                                            value="{{ \Illuminate\Support\Carbon::parse($result->exam_date)->format('d M, Y') }}"
                                            readonly />
                                    </div>
                                </div>

                                <!-- Subject Marks Table -->
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-white">Subject</th>
                                                <th class="text-white">Total Marks</th>
                                                <th class="text-white">Obtained Marks</th>
                                                <th class="text-white">Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($result->subjectMarks as $subjectMark)
                                            <tr data-subject-id="{{ $subjectMark->subject->id }}">
                                                <td class="fw-semibold text-white">{{ $subjectMark->subject->name }}</td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        class="form-control total-mark"
                                                        name="marks[{{ $subjectMark->subject->id }}][total]"
                                                        value="{{ $subjectMark->total_mark }}"
                                                        min="0" />
                                                </td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        class="form-control obtained-mark"
                                                        name="marks[{{ $subjectMark->subject->id }}][obtained]"
                                                        value="{{ $subjectMark->obtained_mark }}"
                                                        min="0" max="100" />
                                                </td>
                                                <td>
                                                    <input 
                                                        type="text" 
                                                        name="marks[{{ $subjectMark->subject->id }}][grade]" 
                                                        class="form-control grade-input text-white fw-bold" 
                                                        value="{{ $subjectMark->grade }}" 
                                                        readonly />
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
                                            value="{{ $result->total_mark }}"
                                            readonly
                                            id="summary-total-marks" />
                                    </div>
                                    <div class="col-md-2">
                                        <x-form-field
                                            type="text"
                                            name="obtained_marks"
                                            label="Obtained Marks"
                                            value="{{ $result->obtained_mark }}"
                                            readonly
                                            id="summary-obtained-marks" />
                                    </div>
                                    <div class="col-md-2">
                                        <x-form-field
                                            type="text"
                                            name="overall_grade"
                                            label="Overall Grade"
                                            value="{{ $result->grade }}"
                                            readonly
                                            id="summary-overall-grade" />
                                    </div>
                                    <div class="col-md-2">
                                        <x-form-field 
                                            type="date" 
                                            name="exam_date" 
                                            label="Exam Date" 
                                            value="{{ $result->exam_date }}" />
                                    </div>
                                    <div class="col-md-2">
                                        <x-form-field
                                            type="select"
                                            name="exam_type"
                                            label="Exam Type">
                                            <option value="final" {{ $result->exam_type == 'final' ? 'selected' : '' }} class="bg-dark text-white">Final Exam</option>
                                            <option value="mid_term" {{ $result->exam_type == 'mid_term' ? 'selected' : '' }} class="bg-dark text-white">Midterm Exam</option>
                                            <option value="test" {{ $result->exam_type == 'test' ? 'selected' : '' }} class="bg-dark text-white">Test</option>
                                        </x-form-field>
                                    </div>
                                    <div class="col-md-2">
                                        <x-form-field
                                            type="select"
                                            name="result_status"
                                            label="Result Status">
                                            <option value="pass" {{ $result->result_status == 'pass' ? 'selected' : '' }} class="bg-dark text-white">Pass</option>
                                            <option value="fail" {{ $result->result_status == 'fail' ? 'selected' : '' }} class="bg-dark text-white">Fail</option>
                                        </x-form-field>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fas fa-save"></i> Update Exam Details
                                        </button>
                                        <a href="{{ route('result.index') }}" class="btn btn-secondary">
                                             Cancel
                                        </a>
                                       
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
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

    // Form validation
    $('form').on('submit', function(e) {
        let valid = true;
        let message = '';

        // Check exam date selected
        if ($('input[name="exam_date"]').val() === "") {
            valid = false;
            message += 'Please select the Exam Date.\n';
        }

        // Validate each subject's marks
        $('tbody tr').each(function() {
            const $row = $(this);
            const total = parseFloat($row.find('input.total-mark').val());
            const obtained = parseFloat($row.find('input.obtained-mark').val());

            if (isNaN(total) || total <= 0) {
                valid = false;
                message += 'Total Marks must be a positive number for all subjects.\n';
                return false; // break .each loop
            }
            if (isNaN(obtained) || obtained < 0 || obtained > total) {
                valid = false;
                message += 'Obtained Marks must be between 0 and Total Marks for all subjects.\n';
                return false;
            }
        });

        if (!valid) {
            alert(message);
            e.preventDefault();
        }
    });

    // Initialize summary calculation on page load
    calculateSummary();
});
</script>
@endpush
