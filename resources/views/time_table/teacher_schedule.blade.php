@extends('layouts.app', ['pageSlug' => 'teacher-schedule'])

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="card-title">My Weekly Schedule</h4>
            <div>
                <span class="badge badge-info">{{ $teacher->user->name }}</span>
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
            <table class="table table-bordered text-white">
                <thead class="bg-primary">
                    <tr>
                        <th style="min-width: 120px;">Time Slot</th>
                        @foreach($weekDays as $day)
                            <th class="text-center">{{ $day->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeSlots as $slot)
                    <tr>
                        <td class="bg-primary">
                            <strong>{{ $slot->period }}</strong>
                        </td>
                        @foreach($weekDays as $day)
                            <td class="schedule-cell text-center" style="min-height: 80px; vertical-align: middle;">
                                @php
                                    $slotName = $slot->name;
                                    $dayName = $day->name;
                                    $hasSchedule = isset($scheduleData[$slotName][$dayName]);
                                @endphp
                                
                                @if($hasSchedule)
                                    <div class="schedule-info p-2">
                                        <div class="badge badge-success mb-1">{{ $scheduleData[$slotName][$dayName]['subject'] }}</div>
                                        <br>
                                        <small class="text-info">{{ $scheduleData[$slotName][$dayName]['class'] }}</small>
                                    </div>
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

        @if(empty($scheduleData))
            <div class="alert alert-info mt-4">
                <div class="text-center">
                    <i class="tim-icons icon-bell-55" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No Schedule Found</h5>
                    <p class="mb-0">You don't have any classes scheduled for this week.</p>
                </div>
            </div>
        @endif
      
    </div>
</div>

@endsection

<style>
    .schedule-cell {
        transition: all 0.3s ease;
    }
    
    .schedule-cell:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        transform: scale(1.02);
    }
    
    .schedule-info {
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .schedule-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .badge {
        font-size: 0.8rem;
    }
</style>
