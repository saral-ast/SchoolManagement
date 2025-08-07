@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Time Table</h4>
        {{-- @can('create.schedules') --}}
        <div class="d-flex justify-content-end">
            <a href="{{ route('schedule.create') }}" class="btn btn-sm btn-primary">Create Time Table</a>
        </div>
        {{-- @endcan --}}
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <x-form-field label="Select Class" name="class_filter" type="select" class="mb-3" id="class-filter">
                    <option value="" class="bg-dark">All Classes</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" class="bg-dark" {{ $classId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </x-form-field>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered text-white">
                <thead>
                    <tr>
                        <th>Time Slot</th>
                        @foreach($weekDays as $day)
                            <th>{{ $day->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeSlots as $slot)
                    <tr>
                        <td>{{ $slot->period }}</td>
                        @foreach($weekDays as $day)
                            <td id="schedule-{{ $slot->name }}-{{ $day->name }}" class="schedule-cell">
                                @php
                                    // Debug what we're looking for in the scheduleData array
                                    $slotName = $slot->name;
                                    $dayName = $day->name;
                                    $hasSchedule = isset($scheduleData[$slotName][$dayName]);
                                @endphp
                                
                                @if($hasSchedule)
                                    <div class="schedule-info">
                                        <p class="mb-1">Subject: {{ $scheduleData[$slotName][$dayName]['subject'] }}</p>
                                        <p class="mb-0">Teacher: {{ $scheduleData[$slotName][$dayName]['teacher'] }}</p>
                                    </div>
                                @else
                                    <span class="text-muted">No class</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('#class-filter').change(function() {
        const classId = $(this).val();
        window.location.href = `{{ route('schedule.index') }}?class_id=${classId}`;
    });
});
</script>
@endpush