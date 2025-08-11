@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Time Table</h4>
        <a href="{{ route('schedule.create') }}" class="btn btn-sm btn-primary">Create Time Table</a>
    </div>

    <div class="card-body">
        @forelse($classes as $class)
        {{-- @dd($class) --}}
            <div class="class-timetable mb-5">
                <h5 class="text-info mb-3">{{ $class->name }} - Time Table</h5>
                
                <div class="table-responsive">
                    <table class="table table-bordered text-white">
                        <thead>
                            <tr>
                                <th>Time Slot</th>
                                @foreach($weekDays as $day)
                                {{-- @dd($day) --}}
                                    <th>{{ ucfirst($day->name) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timeSlots as $slot)
                                <tr>
                                    <td>{{ $slot->period }}</td>

                                    @foreach($weekDays as $day)
                                        @php
                                           if($scheduleData && isset($scheduleData[$class->id])){
                                                 $record = collect($scheduleData[$class->id])->first(function ($item) use ($slot, $day) {
                                                return $item['slot'] == $slot->name 
                                                    && strtolower($item['day']) == strtolower($day->name);
                                            });
                                           } else {
                                              $record = null;
                                           }
                                           

                                        @endphp

                                        <td class="schedule-cell">
                                            @if($record)
                                                <div class="schedule-info">
                                                    <p class="mb-1"><strong>{{ $record['subject'] }}</strong></p>
                                                    <p class="mb-0 text-muted">{{ $record['teacher'] }}</p>
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
        @empty
            <div class="alert alert-info">
                No classes found or no schedule data available.
            </div>
        @endforelse
    </div>
</div>
@endsection