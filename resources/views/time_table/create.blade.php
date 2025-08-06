@extends('layouts.app')

@section('content')
<form action="{{ route('schedule.store') }}" method="POST">
    @csrf

    <x-form-field label="Select Class" name="class" type="select" class="mb-3">
        @foreach ($allClass as $class)
            <option value="{{ $class->id }}" class="bg-dark">{{ $class->name }}</option>
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
    // Attach event to all .subject-select (each row's subject select, not just first)
    $(document).on('change', '.subject-select', function(){
        const subjectId = $(this).val();
        const $row = $(this).closest('tr');
        const $teacherSelect = $row.find('.teacher-select');
        const period = $row.find('input[type=hidden][name^=period]').val();

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
                    period: period
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data){
                    console.log('donr');
                    // Populate $teacherSelect here based on response
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