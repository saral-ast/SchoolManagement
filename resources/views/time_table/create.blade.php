@extends('layouts.app')

@section('content')
<form action="{{ route('schedule.store') }}" method="POST">
    @csrf

    <x-form-field label="Select Class" name="class" type="select" class="mb-3">
        @foreach ($allClass as $class)
            <option value="{{ $class->id }}" class="bg-dark">{{ $class->name }}</option>
        @endforeach
    </x-form-field>

    <x-form-field label="Select Day" name="day" type="select" class="mb-3" id="schedule-day">
        @foreach($weekDays as $day)
            <option value="{{ $day->name }}" class="bg-dark">{{ $day->name }}</option>
        @endforeach
    </x-form-field>

    <x-form-field label="Start Date" name="start_date" type="date" class="mb-3" />

    <x-form-field label="End Date" name="end_date" type="date" class="mb-3" />

    <div class="table-responsive mb-4">
        <table class="table table-bordered text-white align-middle">
            <thead>
                <tr>
                    <th>Slot name</th>
                    <th>Select Subject</th>
                    <th>Select Teacher</th>
                    <th>Time Period</th>
                </tr>
            </thead>
            <tbody>
                @foreach($totalSlot as $key => $slot)
                <tr>
                    <td>{{ $slot->name }}</td>

                    <td>
                        <x-form-field label="Subject" name="subject[{{ $key }}]" type="select" class='subject-select' required>
                                    
                            <option value="" class="bg-dark">Select Subject</option>

                            @foreach ($allSubject as $subject)
                                <option value="{{ $subject->id }}" class="bg-dark">{{ $subject->name }}</option>
                            @endforeach
                        </x-form-field>
                    </td>

                    <td>
                        <x-form-field label="Teacher" name="teacher[{{ $key }}]" type="select" required class="teacher-select bg-dark">
                            <option value="" class="bg-dark">Select Teacher</option>      
                        </x-form-field>
                    </td>

                    <td>
                        {{ $slot->period ?? 'N/A' }}
                        {{-- Hide period input for submission --}}
                        <input type="hidden" name="period[{{ $key }}]" value="{{ $slot->period }}" />
                    </td>

                    {{-- Hidden slot name --}}
                    <input type="hidden" name="slot[{{ $key }}]" value="{{ $slot->name }}" />
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection

@push('js')

<script>
$(document).ready(function(){
    // Function to get the next occurrence of a specific weekday
    function getNextWeekday(weekday) {
        const weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const targetDay = weekdays.indexOf(weekday);
        const today = new Date();
        const currentDay = today.getDay();
        
        let daysUntilTarget = targetDay - currentDay;
        if (daysUntilTarget <= 0) {
            daysUntilTarget += 7; // Move to next week
        }
        
        const nextDate = new Date(today);
        nextDate.setDate(today.getDate() + daysUntilTarget);
        return nextDate;
    }
    
    // Function to restrict date inputs based on selected day
    function restrictDateInputs() {
        const selectedDay = $('#schedule-day').val();
        const startDateInput = $('input[name="start_date"]');
        const endDateInput = $('input[name="end_date"]');
        
        if (selectedDay) {
            // Get the next occurrence of the selected weekday
            const nextWeekday = getNextWeekday(selectedDay);
            
            // Set minimum date for start_date (next occurrence of selected weekday)
            const minStartDate = nextWeekday.toISOString().split('T')[0];
            startDateInput.attr('min', minStartDate);
            
            // Clear existing values if they don't match the selected day
            if (startDateInput.val()) {
                const startDate = new Date(startDateInput.val());
                const startDay = startDate.toLocaleDateString('en-US', { weekday: 'long' });
                if (startDay !== selectedDay) {
                    startDateInput.val('');
                }
            }
            
            if (endDateInput.val()) {
                const endDate = new Date(endDateInput.val());
                const endDay = endDate.toLocaleDateString('en-US', { weekday: 'long' });
                if (endDay !== selectedDay) {
                    endDateInput.val('');
                }
            }
        }
    }
    
    // Restrict dates when day selection changes
    $('#schedule-day').on('change', function() {
        restrictDateInputs();
    });
    
    // Validate start_date selection
    $('input[name="start_date"]').on('change', function() {
        const selectedDay = $('#schedule-day').val();
        const selectedDate = new Date(this.value);
        const selectedDayName = selectedDate.toLocaleDateString('en-US', { weekday: 'long' });
        
        if (selectedDay && selectedDayName !== selectedDay) {
            alert(`Please select a ${selectedDay} date.`);
            this.value = '';
            return;
        }
        
        // Update end_date min attribute
        $('input[name="end_date"]').attr('min', this.value);
    });
    
    // Validate end_date selection
    $('input[name="end_date"]').on('change', function() {
        const selectedDay = $('#schedule-day').val();
        const selectedDate = new Date(this.value);
        const selectedDayName = selectedDate.toLocaleDateString('en-US', { weekday: 'long' });
        
        if (selectedDay && selectedDayName !== selectedDay) {
            alert(`Please select a ${selectedDay} date.`);
            this.value = '';
            return;
        }
        
        const startDate = $('input[name="start_date"]').val();
        if (startDate && this.value < startDate) {
            alert('End date must be after start date.');
            this.value = '';
            return;
        }
    });
    
    // Initial restriction
    restrictDateInputs();
    
    // Attach event to all .subject-select (each row's subject select, not just first)
    $(document).on('change', '.subject-select', function(){
        const subjectId = $(this).val();
        const $row = $(this).closest('tr');
        const $teacherSelect = $row.find('.teacher-select');
        const period = $row.find('input[type=hidden][name^=period]').val();
        const day = $('#schedule-day').val();
        const startDate = $('input[name="start_date"]').val();
        const endDate = $('input[name="end_date"]').val();
        $teacherSelect.empty().append('<option class="bg-dark text-white" selected>Loading...</option>');


        if(subjectId){
            $.ajax({
                url: `{{ route('suject.teachers', ['id' => '__SUBJECT_ID__']) }}`.replace('__SUBJECT_ID__', subjectId),
                type: 'GET',
                dataType: 'json',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    period: period,
                    day: day
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data){
                   
                    $teacherSelect.empty();
                   $teacherSelect.append('<option value="">Select Teacher</option>');

                    // Populate with new data
                    if (data.teachers && data.teachers.length) {
                        data.teachers.forEach(function(teacher){
                            // Check for user relation
                            const teacherName = teacher.user ? teacher.user.name : 'Unknown';
                            $teacherSelect.append(
                                `<option value="${teacher.id}" class='bg-dark'>${teacherName}</option>`
                            );
                        });
                    } else {
                        $teacherSelect.append(`<option value="" class='bg-dark text-white'>No teachers available</option>`);
                    }
                    }
                });
            }
    });
});
</script>

    
@endpush