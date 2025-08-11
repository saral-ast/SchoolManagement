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
                                                    $slotMatch = $item['slot'] == $slot->name;
                                                    $dayMatch = strtolower($item['day']) == strtolower($day->name);
                                                    
                                                    if (!$slotMatch || !$dayMatch) {
                                                        return false;
                                                    }
                                                    
                                                    // For proxy schedules, show only on the exact start_date
                                                    if (isset($item['proxy']) && $item['proxy']) {
                                                        $targetDate = \Carbon\Carbon::parse($item['start_date'])->format('Y-m-d');
                                                        $dayDateStr = $day->date->format('Y-m-d');
                                                        return $dayDateStr === $targetDate;
                                                    }
                                                    
                                                    // For regular schedules, check if the date falls within the schedule range
                                                    $scheduleStart = \Carbon\Carbon::parse($item['start_date']);
                                                    $scheduleEnd = \Carbon\Carbon::parse($item['end_date']);
                                                    $dayDate = $day->date;
                                                    return $dayDate->between($scheduleStart, $scheduleEnd);
                                                });
                                           } else {
                                              $record = null;
                                           }
                                        @endphp

                                        <td class="schedule-cell" style="min-height: 80px;">
                                            @if($record)
                                                <div class="schedule-info p-2 rounded d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <div class="mb-1">{{ $record['subject'] }}</div>
                                                        <div class="text-muted small mb-1">
                                                            <i class="fas fa-user"></i> {{ $record['teacher'] }}
                                                            @if(isset($record['proxy']) && $record['proxy'])
                                                                <span class="badge badge-warning" style="font-size: 0.7em;">Proxy</span>
                                                            @endif
                                                        </div>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-calendar"></i>
                                                            {{ \Carbon\Carbon::parse($record['start_date'])->format('M j') }} - 
                                                            {{ \Carbon\Carbon::parse($record['end_date'])->format('M j') }}
                                                        </div>
                                                    </div>
                                                    @if(auth()->user() && auth()->user()->user_type() === 'admin')
                                                        <a class="text-warning ms-2" title="Edit" href="{{ route('schedule.edit', [
                                                            'schedule_id' => $record['schedule_id'],
                                                            'date' => $day->date->format('Y-m-d')
                                                        ]) }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
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
