@extends('layouts.app')

@section('content')
@php
    $slotToPeriod = [
        'slot1' => '09:00 - 10:00',
        'slot2' => '10:00 - 11:00',
        'slot3' => '12:00 - 13:00',
        'slot4' => '13:00 - 14:00',
        'slot5' => '14:00 - 15:00',
    ];
@endphp
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Edit Slot (One Day Override)</h4>
        <a href="{{ route('schedule.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="mb-3 p-3 rounded">
            <h6 class="mb-2">Schedule Details</h6>
            <div class="row">
                <div class="col-md-6">
                    <strong>Class:</strong> {{ $class->name }}<br>
                    <strong>Subject:</strong> {{ $subject->name }}<br>
                    <strong>Slot:</strong> {{ $metadata['slot'] }} ({{ $slotToPeriod[$metadata['slot']] ?? '09:00 - 10:00' }})<br>
                </div>
                <div class="col-md-6">
                    <strong>Day:</strong> {{ ucfirst($metadata['day']) }}<br>
                    <strong>Current Teacher:</strong> {{ optional($schedule->schedulable->user)->name ?? 'Teacher #'.$schedule->schedulable_id }}<br>
                    <strong>Current Schedule Period:</strong> {{ \Carbon\Carbon::parse($schedule->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($schedule->end_date)->format('M j, Y') }}<br>
                    <strong>Overall Slot Period:</strong> {{ \Carbon\Carbon::parse($overallStartDate)->format('M j') }} - {{ \Carbon\Carbon::parse($overallEndDate)->format('M j, Y') }}
                </div>
            </div>
        </div>

        <form action="{{ route('schedule.update') }}" method="POST">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            
            <div class="form-group mb-3">
                <label for="date" class="form-label">Select Date to Override</label>
                <input type="date" 
                       name="date" 
                       id="date"
                       class="form-control" 
                       min="{{ $overallStartDate }}" 
                       max="{{ $overallEndDate }}"
                       value="{{ $clickedDate ?? request('date', now()->format('Y-m-d')) }}" />
                <small class="text-muted">Note: This will only affect {{ ucfirst($metadata['day']) }}s within the overall slot period ({{ \Carbon\Carbon::parse($overallStartDate)->format('M j') }} - {{ \Carbon\Carbon::parse($overallEndDate)->format('M j, Y') }}).</small>
                <div class="invalid-feedback">Please select a {{ ucfirst($metadata['day']) }}.</div>
                <div class="valid-feedback">Valid {{ ucfirst($metadata['day']) }} selected.</div>
            </div>

            <div class="form-group mb-3">
                <label for="proxy_teacher_id" class="form-label">Proxy Teacher for this date</label>
                <select name="proxy_teacher_id" id="proxy_teacher_id" class="form-control" required>
                    <option value="">Select Teacher</option>
                </select>
            </div>


            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit" id="submitBtn" disabled>Update Schedule</button>
                <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>



@push('js')
<script>
$(function () {
    const $dateInput = $('input[name="date"]');
    const $submitBtn = $('#submitBtn');
    const weekday = '{{ strtolower($metadata["day"]) }}';
    const subjectId = '{{ $metadata['subject_id'] }}';
    const currentTeacherId = Number('{{ $schedule->schedulable_id }}');
    const period = '{{ $slotToPeriod[$metadata['slot']] ?? '09:00 - 10:00' }}';
    const $teacherSelect = $('#proxy_teacher_id');

    const weekdays = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    function setTeacherSelectLoading() {
        $teacherSelect.empty()
            .append($('<option>').text('Loading...').val(''))
            .prop('disabled', true);
    }

    function setTeacherSelectEmpty() {
        $teacherSelect.empty()
            .append($('<option>').text('No teachers available').val(''))
            .prop('disabled', true);
    }

    function populateTeachers(teachers) {
        $teacherSelect.empty().append($('<option>').text('Select Teacher').val('')).addClass('bg-dark');
        let hasAny = false;

        $.each(teachers, function (_, t) {
            if (Number(t.id) === currentTeacherId) return;
            const name = t.user ? t.user.name : `Teacher #${t.id}`;
            $teacherSelect.append($('<option>').val(t.id).text(name));
            hasAny = true;
        });

        $teacherSelect.prop('disabled', !hasAny);
        if (!hasAny) setTeacherSelectEmpty();
    }

    function fetchAvailableTeachers() {
        const selectedDate = $dateInput.val();
        if (!selectedDate) return setTeacherSelectEmpty();

        setTeacherSelectLoading();

        const url = `{{ route('suject.teachers', ['id' => '__SUBJECT_ID__']) }}`.replace('__SUBJECT_ID__', subjectId);
        const params = {
            start_date: selectedDate,
            end_date: selectedDate,
            period: period,
            day: weekday
        };

        $.getJSON(url, params)
            .done(function (data) {
                if (data && Array.isArray(data.teachers)) {
                    populateTeachers(data.teachers);
                } else {
                    setTeacherSelectEmpty();
                }
            })
            .fail(setTeacherSelectEmpty);
    }

    function validateDate() {
        const selectedDate = new Date($dateInput.val());
        const selectedWeekday = weekdays[selectedDate.getDay()];

        if (selectedWeekday === weekday) {
            $submitBtn.prop('disabled', false);
            $dateInput.removeClass('is-invalid').addClass('is-valid');
            fetchAvailableTeachers();
        } else {
            $submitBtn.prop('disabled', true);
            $dateInput.removeClass('is-valid').addClass('is-invalid');
            setTeacherSelectEmpty();
        }
    }

    $dateInput.on('change', validateDate);
    validateDate();
});

</script>
@endpush
@endsection


