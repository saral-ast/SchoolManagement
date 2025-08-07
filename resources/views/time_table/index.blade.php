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
        @foreach($classes as $class)
            <div class="class-timetable mb-4">
                <h5 class="text-info mb-3">{{ $class->name }} - Time Table</h5>
                
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
                                    <td id="schedule-{{ $class->id }}-{{ $slot->name }}-{{ $day->name }}" class="schedule-cell">
                                        @php
                                            $slotName = $slot->name;
                                            $dayName = $day->name;
                                            $classId = $class->id;
                                            $hasSchedule = isset($scheduleData[$classId][$slotName][$dayName]);
                                        @endphp
                                        
                                        @if($hasSchedule)
                                            <div class="schedule-info">
                                                <p class="mb-1"><strong>{{ $scheduleData[$classId][$slotName][$dayName]['subject'] }}</strong></p>
                                                <p class="mb-0 text-muted">{{ $scheduleData[$classId][$slotName][$dayName]['teacher'] }}</p>
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
            
            @if(!$loop->last)
                <hr class="my-4">
            @endif
        @endforeach
        
        @if($classes->isEmpty())
            <div class="alert alert-info">
                <p class="mb-0">No classes found or no schedule data available.</p>
            </div>
        @endif
    </div>
</div>
@endsection
