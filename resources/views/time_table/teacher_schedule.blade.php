@extends('layouts.app', ['pageSlug' => 'teacher-schedule'])

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="card-title">My Weekly Schedule</h4>
            <div class="d-flex align-items-center gap-3">
                <div class="btn-group" role="group">
                    <a href="?week_start={{ $weekStart->copy()->subWeek()->format('Y-m-d') }}" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-chevron-left"></i> Previous Week
                    </a>
                    <span class="btn btn-sm btn-primary">
                        Week {{ $weekStart->format('W, o') }}: {{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}
                    </span>
                    <a href="?week_start={{ $weekStart->copy()->addWeek()->format('Y-m-d') }}" 
                       class="btn btn-sm btn-outline-primary">
                        Next Week <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-info">Teacher Information</h6>
                <p><strong>Name:</strong> {{ $teacher->user->name }}</p>
                <p><strong>Email:</strong> {{ $teacher->user->email }}</p>
                <p><strong>Phone:</strong> {{ $teacher->phone ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-info">Subjects Taught</h6>
                @if($teacher->subjects->count() > 0)
                    @foreach($teacher->subjects as $subject)
                        <span class="badge badge-primary mr-2">{{ $subject->name }}</span>
                    @endforeach
                @else
                    <p class="text-muted">No subjects assigned</p>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="table  text-white">
                <thead class="">
                    <tr>
                        <th style="min-width: 120px;">Time Slot</th>
                        @foreach($weekDaysWithDates as $day)
                            <th class="text-center">
                                <div class="fw-bold">{{ ucfirst($day->name) }}</div>
                                <small class="text-light opacity-75">{{ $day->formatted_date }}</small>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeSlots as $slot)
                    <tr>
                        <td class="">
                            <strong>{{ $slot->period }}</strong>
                        </td>
                        @foreach($weekDaysWithDates as $day)
                            <td class="schedule-cell text-center" style="min-height: 80px; vertical-align: middle;">
                                @php
                                    $slotName = $slot->name;
                                    $dayName = strtolower($day->name);
                                    $hasSchedule = isset($scheduleData[$slotName][$dayName]);
                                @endphp
                                
                                @if($hasSchedule)
                                    @php
                                        $schedule = $scheduleData[$slotName][$dayName];
                                        $isProxy = $schedule['proxy'] ?? false;
                                        
                                        // For proxy schedules, only show on the specific date
                                        if ($isProxy) {
                                            $proxyDate = $schedule['proxy_date'] ?? $schedule['start_date'];
                                            $currentDate = $day->date->format('Y-m-d');
                                            $shouldShowProxy = ($currentDate === $proxyDate);
                                        } else {
                                            $shouldShowProxy = true;
                                        }
                                    @endphp
                                    
                                    @if($shouldShowProxy)
                                        <div class="schedule-info p-2">
                                            <div class="badge mb-1">
                                                {{ $schedule['subject'] }}
                                                @if($isProxy)
                                                    <span class="badge bg-danger ms-1">Proxy</span>
                                                @endif
                                            </div>
                                            <br>
                                            <small>{{ $schedule['class'] }}</small>

                                        </div>
                                    @else
                                        {{-- Show "Free" when proxy schedule exists but shouldn't be displayed on this date --}}
                                        <div class="text-muted">
                                            <i class="tim-icons icon-time"></i>
                                            <br>
                                            <small>Free</small>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-muted">
                                        <i class="tim-icons icon-time"></i>
                                        <br>
                                        <small>Free</small>
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
</div>

@endsection


