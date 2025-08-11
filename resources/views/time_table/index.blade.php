@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Weekly Time Table</h4>
        <div class="d-flex gap-2 align-items-center">
            <!-- Week Filter -->
            <form method="GET" class="d-flex align-items-center gap-2">
                <label for="week" class="form-label mb-0">Select Week:</label>
                <input type="week" 
                       id="week" 
                       name="week" 
                       class="form-control form-control-sm" 
                       value="{{ $selectedWeek }}"
                       onchange="this.form.submit()">
            </form>
            
            <!-- Week Navigation -->
            <div class="btn-group" role="group">
                <a href="{{ route('schedule.index', ['week' => $weekStart->copy()->subWeek()->format('o-\WW')]) }}" 
                   class="btn btn-outline-secondary btn-sm" title="Previous Week">← Prev</a>
                <a href="{{ route('schedule.index', ['week' => now()->format('o-\WW')]) }}" 
                   class="btn btn-outline-primary btn-sm" title="Current Week">This Week</a>
                <a href="{{ route('schedule.index', ['week' => $weekStart->copy()->addWeek()->format('o-\WW')]) }}" 
                   class="btn btn-outline-secondary btn-sm" title="Next Week">Next →</a>
            </div>
            
            <a href="{{ route('schedule.create') }}" class="btn btn-sm btn-primary">Create Time Table</a>
        </div>
    </div>

    <div class="card-body">
        <!-- Week Information Banner -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 border rounded">
            <div>
                <strong>Week {{ $weekStart->format('W, o') }}:</strong> 
                {{ $weekStart->format('l, M j') }} - {{ $weekEnd->format('l, M j, Y') }}
            </div>
        </div>

        @forelse($classes as $class)
            <div class="class-timetable mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">{{ $class->name }} - Weekly Schedule</h5>
                    <small class="text-muted">
                        @if(isset($scheduleData[$class->id]))
                            {{ count($scheduleData[$class->id]) }} classes scheduled
                        @else
                            No classes scheduled
                        @endif
                    </small>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="">
                            <tr>
                                <th style="width: 150px;">Time Slot</th>
                                @foreach($weekDaysWithDates as $day)
                                    <th class="text-center">
                                        <div>{{ ucfirst($day->name) }}</div>
                                        <small class="">{{ $day->formatted_date }}</small>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timeSlots as $slot)
                                <tr>
                                    <td class="">{{ $slot->period }}</td>

                                    @foreach($weekDaysWithDates as $day)
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

                                        <td class="schedule-cell" style="min-height: 80px;">
                                            @if($record)
                                                <div class="schedule-info p-2 rounded">
                                                    <div class="mb-1">{{ $record['subject'] }}</div>
                                                    <div class="text-muted small mb-1">
                                                        <i class="fas fa-user"></i> {{ $record['teacher'] }}
                                                    </div>
                                                    <div class="small text-muted">
                                                        <i class="fas fa-calendar"></i>
                                                        {{ \Carbon\Carbon::parse($record['start_date'])->format('M j') }} - 
                                                        {{ \Carbon\Carbon::parse($record['end_date'])->format('M j') }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center text-muted py-3">
                                                    <small>No class</small>
                                                </div>
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
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                No classes found or no schedule data available for week {{ $weekStart->format('W, o') }}.
            </div>
        @endforelse
    </div>
</div>

@endsection
