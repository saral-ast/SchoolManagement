<?php

namespace App\Service;

use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use Zap\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Zap\Facades\Zap;

class ScheduleService
{
    /**
     * Create a new class instance.
     */
    /**
     * Get classes based on user role
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getClasses($user)
    {
        $userType = $user->user_type();
        
        // Admin and Teacher can see all classes
        switch ($userType) {
            case 'admin':
            case 'teacher':
                return Classes::all();
                // break;
                
            case 'student':
                $student = $user->student()->first();
                if ($student && $student->class_id) {
                    return Classes::where('id', $student->class_id)->get();
                }
                break;
                
            case 'student_parent':
                $parent = $user->student_parent()->first();
                if ($parent && $parent->students) {
                    $classIds =  $parent->students->pluck('class_id')->unique();
                    return Classes::whereIn('id',$classIds)->get();
                }
                break;
                
            default:
               
                dd('Invalid user type');
                break;
        }

        
        return collect([]);
    }
    public function availableTeachers($teachers, $start_date, $end_date, $start_time, $end_time, $weekday)
    {
        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);
        $weekday = strtolower($weekday); 
        
        $availableTeachers = $teachers->filter(function($teacher) use($start, $end, $start_time, $end_time, $weekday) {
            
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                
                if (strtolower($date->format('l')) === $weekday) {
                    if (!$teacher->isAvailableAt($date->toDateString(), $start_time, $end_time)) {
                        return false; 
                    }
                }
            }
            
            return true; 
        })->values();
        
        return $availableTeachers;
    }
    public function addSchedule($data){
            $classId = $data['class'];
            $startDate = $data['start_date'];
            $endDate = $data['end_date'];
            $subjects = $data['subject'];
            $teachers = $data['teacher'];
            $slots = $data['slot'];
            $periods = $data['period'];
            $day = $data['day'];

            
        $count = count($slots);
        for ($i=0; $i <$count ; $i++) { 
            $subjectId = $subjects[$i];
            $teacherId = $teachers[$i];
            $slotName = $slots[$i];
            $weekday = strtolower($day);

            [$startTime,$endTIme] = array_map('trim',explode('-',$periods[$i]));
            
            $teacher = Teacher::findOrFail($teacherId);
            
             Zap::for($teacher)->named("Subject {$subjectId} to Class {$classId} - {$slotName} ")
            ->appointment()
            ->from($startDate)
            ->to($endDate)
            ->addPeriod($startTime,$endTIme)
            ->weekly([$weekday])
            ->withMetadata([
                'subject_id' => $subjectId,
                'class_id' =>  $classId,
                'slot' => $slotName,
                'day' => $weekday
            ])->save();
        }
    }


   public function splitAppointmentForOneDay(
     $scheduleId,
     $dateYmd,
     $proxyTeacherId,
     
) {
    $targetDate = CarbonImmutable::parse($dateYmd);

   
    $originalSchedule = Schedule::findOrFail($scheduleId);
    $metadata = $originalSchedule->metadata;

  
    $original = Teacher::findOrFail($originalSchedule->schedulable_id);

    $classId   = $metadata['class_id'];
    $subjectId = $metadata['subject_id'];
    $slotName  = $metadata['slot'];
    $weekday   = strtolower($metadata['day']);

   
    [$startTime, $endTime] = $slotToPeriod[$slotName] ?? ['09:00', '10:00'];

    $proxyTeacher = Teacher::findOrFail($proxyTeacherId);
     $slotToPeriod = [
        'slot1' => ['09:00', '10:00'],
        'slot2' => ['10:00', '11:00'],
        'slot3' => ['12:00', '13:00'],
        'slot4' => ['13:00', '14:00'],
        'slot5' => ['14:00', '15:00'],
    ];
    // dd($proxyTeacherId);
    DB::transaction(function () use (
        $original,
        $originalSchedule,
        $classId,
        $subjectId,
        $slotName,
        $weekday,
        $startTime,
        $endTime,
        $targetDate,
        $proxyTeacher
    ) {
        $origStart = CarbonImmutable::parse($originalSchedule->start_date);
        $origEnd   = CarbonImmutable::parse($originalSchedule->end_date);

        
        
        if ($targetDate->eq($origStart)) {
            // dd($targetDate);
            $originalSchedule->delete();
        } elseif ($targetDate->eq($origEnd)) {
         
            $beforeEnd = $targetDate->subDay();
            if ($beforeEnd->gte($origStart)) {
                $originalSchedule->update(['end_date' => $beforeEnd->format('Y-m-d')]);
            }
        } elseif ($targetDate->isAfter($origStart) && $targetDate->isBefore($origEnd)) {
            $beforeEnd = $targetDate->subDay();
            if ($beforeEnd->gte($origStart)) {
                $originalSchedule->update(['end_date' => $beforeEnd->format('Y-m-d')]);
            }
        }


        // Create proxy appointment with conditional end date
        $proxyAppointment = Zap::for($proxyTeacher)
            ->named("Proxy: Subject {$subjectId} to Class {$classId} - {$slotName}")
            ->appointment()
            ->from($targetDate->format('Y-m-d'));
        
        $proxyEndDate = $targetDate->addWeek();
        $proxyAppointment->to($proxyEndDate->format('Y-m-d'));
        
        $proxyAppointment
            ->addPeriod($startTime, $endTime)
            ->withMetadata([
                'subject_id' => $subjectId,
                'class_id'   => $classId,
                'slot'       => $slotName,
                'day'        => $weekday,
                'proxy'      => true
            ])
            ->save();

  
    //   dd($targetDate);
        if ($targetDate->eq($origStart)) {
            $afterStart = $targetDate->addWeek();
            if ($afterStart->lte($origEnd)) {
                Zap::for($original)
                    ->named("Subject {$subjectId} to Class {$classId} - {$slotName} (contd)")
                    ->appointment()
                    ->from($afterStart->format('Y-m-d'))
                    ->to($origEnd->format('Y-m-d'))
                    ->addPeriod($startTime, $endTime)
                    ->weekly([$weekday])
                    ->withMetadata([
                        'subject_id' => $subjectId,
                        'class_id'   => $classId,
                        'slot'       => $slotName,
                        'day'        => $weekday
                    ])
                    ->save();
            }
        } elseif ($targetDate->isAfter($origStart) && $targetDate->isBefore($origEnd)) {
            $afterStart = $targetDate->addDay();
            if ($afterStart->lte($origEnd)) {
                Zap::for($original)
                    ->named("Subject {$subjectId} to Class {$classId} - {$slotName} (contd)")
                    ->appointment()
                    ->from($afterStart->format('Y-m-d'))
                    ->to($origEnd->format('Y-m-d'))
                    ->addPeriod($startTime, $endTime)
                    ->weekly([$weekday])
                    ->withMetadata([
                        'subject_id' => $subjectId,
                        'class_id'   => $classId,
                        'slot'       => $slotName,
                        'day'        => $weekday
                    ])
                    ->save();
            }
        }
    });
}

  
    public function getSchedulesForWeek($classes, $weekStart, $weekEnd)
    {
        if ($classes->count() === 0) {
            return [];
        }

        $classIds = $classes->pluck('id')->toArray();
        
        $teachers = Teacher::with(['user', 'appointmentSchedules' => function ($query) use ($classIds, $weekStart, $weekEnd) {
            $query->whereIn('metadata->class_id', $classIds)
                ->whereNotNull('metadata->slot')
                ->whereNotNull('metadata->subject_id')
                ->whereNotNull('metadata->day')
                ->where(function ($q) use ($weekStart, $weekEnd) {
                    $q->whereDate('start_date', '<=', $weekEnd)
                      ->whereDate('end_date', '>=', $weekStart);
                });
        }])->whereHas('appointmentSchedules', function ($query) use ($classIds, $weekStart, $weekEnd) {
            $query->whereIn('metadata->class_id', $classIds)
                ->whereDate('start_date', '<=', $weekEnd)
                ->whereDate('end_date', '>=', $weekStart);
        })->get();

        $subjectIds = $teachers->flatMap(function ($teacher) {
            return $teacher->appointmentSchedules->pluck('metadata.subject_id');
        })->filter()->unique();

        $subjects = Subject::whereIn('id', $subjectIds)->pluck('name', 'id');
        
        $scheduleData = [];
        
        foreach ($teachers as $teacher) {
            foreach ($teacher->appointmentSchedules as $schedule) {
                $metadata = $schedule->metadata;
                $classId = $metadata['class_id'];
                $subjectId = $metadata['subject_id'];
                
                if (isset($subjects[$subjectId])) {
                    $scheduleData[$classId][] = [
                        'class_id' => $classId,
                        'slot' => $metadata['slot'],
                        'day' => ucfirst($metadata['day']),
                        'subject' => $subjects[$subjectId],
                        'subject_id' => $subjectId,
                        'teacher_id' => $teacher->id,
                        'schedule_id' => $schedule->id,
                        'teacher' => $teacher->user ? $teacher->user->name : 'N/A',
                        'start_date' => $schedule->start_date,
                        'end_date' => $schedule->end_date,
                        'proxy' => isset($metadata['proxy']) ? $metadata['proxy'] : false
                    ];
                }
            }
        }

        return $scheduleData;
    }

    /**
     * Get teacher schedules for a specific week
     */
    public function getTeacherSchedulesForWeek($teacher, $weekStart, $weekEnd)
    {
        $schedules = $teacher->schedules()
            ->where(function($query) use ($weekStart, $weekEnd) {
                $query->whereBetween('start_date', [$weekStart, $weekEnd])
                      ->orWhereBetween('end_date', [$weekStart, $weekEnd])
                      ->orWhere(function($q) use ($weekStart, $weekEnd) {
                          $q->where('start_date', '<=', $weekStart)
                            ->where('end_date', '>=', $weekEnd);
                      });
            })
            ->orderBy('start_date')
            ->get();

        $scheduleData = [];
        
        foreach ($schedules as $schedule) {
            $metadata = $schedule->metadata;
            $class = Classes::find($metadata['class_id']);
            $subject = Subject::find($metadata['subject_id']);
            
            if ($class && $subject) {
                $isProxy = $metadata['proxy'] ?? false;
                
                if ($isProxy) {
                    // For proxy schedules, only show on the specific start date
                    $proxyDate = Carbon::parse($schedule->start_date);
                    $dayName = strtolower($proxyDate->format('l'));
                    
                    $scheduleData[$metadata['slot']][$dayName] = [
                        'schedule_id' => $schedule->id,
                        'subject' => $subject->name,
                        'class' => $class->name,
                        'teacher' => $teacher->user->name,
                        'slot' => $metadata['slot'],
                        'day' => $dayName,
                        'start_date' => $schedule->start_date,
                        'end_date' => $schedule->end_date,
                        'proxy' => true,
                        'is_proxy' => true,
                        'proxy_date' => $proxyDate->format('Y-m-d')
                    ];
                } else {
                    // For regular schedules, show across the entire range
                    $scheduleData[$metadata['slot']][$metadata['day']] = [
                        'schedule_id' => $schedule->id,
                        'subject' => $subject->name,
                        'class' => $class->name,
                        'teacher' => $teacher->user->name,
                        'slot' => $metadata['slot'],
                        'day' => $metadata['day'],
                        'start_date' => $schedule->start_date,
                        'end_date' => $schedule->end_date,  
                        'proxy' => false,
                        'is_proxy' => false
                    ];
                }
            }
        }

        return $scheduleData;
    }

 
    public function generateWeekDaysWithDates($weekStart)
    {
        $weekDays = [
            (object)['id' => 1, 'name' => 'Monday'],
            (object)['id' => 2, 'name' => 'Tuesday'],
            (object)['id' => 3, 'name' => 'Wednesday'],
            (object)['id' => 4, 'name' => 'Thursday'],
            (object)['id' => 5, 'name' => 'Friday'],
        ];

        return collect($weekDays)->map(function ($day, $index) use ($weekStart) {
            $dayDate = $weekStart->copy()->addDays($index);
            return (object)[
                'name' => $day->name,
                'date' => $dayDate,
                'formatted_date' => $dayDate->format('M j'),
                'is_today' => $dayDate->isToday(),
            ];
        });
    }
}