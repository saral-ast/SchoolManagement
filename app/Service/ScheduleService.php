<?php

namespace App\Service;

use App\Models\Classes;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
    int $scheduleId,
    string $dateYmd,
    int $proxyTeacherId
) {
    $targetDate = CarbonImmutable::parse($dateYmd);

    /** @var \Zap\Models\Schedule $originalSchedule */
    $originalSchedule = \Zap\Models\Schedule::findOrFail($scheduleId);
    $metadata = $originalSchedule->metadata;

    /** @var \App\Models\Teacher $original */
    $original = Teacher::findOrFail($originalSchedule->schedulable_id);

    $classId   = $metadata['class_id'];
    $subjectId = $metadata['subject_id'];
    $slotName  = $metadata['slot'];
    $weekday   = strtolower($metadata['day']);

    // Recommended: move slot periods to config or const
    $slotToPeriod = [
        'slot1' => ['09:00', '10:00'],
        'slot2' => ['10:00', '11:00'],
        'slot3' => ['12:00', '13:00'],
        'slot4' => ['13:00', '14:00'],
        'slot5' => ['14:00', '15:00'],
    ];
    [$startTime, $endTime] = $slotToPeriod[$slotName] ?? ['09:00', '10:00'];

    $proxyTeacher = Teacher::findOrFail($proxyTeacherId);

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

        // 1) Shorten original schedule before the proxy day
        if ($targetDate->isAfter($origStart)) {
            $beforeEnd = $targetDate->subDay();
            if ($beforeEnd->gte($origStart)) {
                $originalSchedule->update(['end_date' => $beforeEnd->format('Y-m-d')]);
            }
        } else {
            // If proxy date is the very first day
            $originalSchedule->update(['end_date' => $origStart->format('Y-m-d')]);
        }

        // 2) One-day proxy appointment (no weekly recurrence)
        Zap::for($proxyTeacher)
            ->named("Proxy: Subject {$subjectId} to Class {$classId} - {$slotName}")
            ->appointment()
            ->from($targetDate->format('Y-m-d'))
            ->to($targetDate->format('Y-m-d'))
            ->addPeriod($startTime, $endTime)
            ->withMetadata([
                'subject_id' => $subjectId,
                'class_id'   => $classId,
                'slot'       => $slotName,
                'day'        => $weekday,
                'proxy'      => true
            ])
            ->save();

        // 3) Resume original teacher after proxy day if needed
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
    });
}
   
}