<?php

namespace App\Service;

use App\Models\Teacher;
use Carbon\Carbon;
use Zap\Facades\Zap;

class ScheduleService
{
    /**
     * Create a new class instance.
     */
    public function availableTeachers($teachers, $start_date, $end_date, $start_time, $end_time, $weekday)
    {
        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);
        $weekday = strtolower($weekday); // e.g., 'monday', 'tuesday'
        
        $availableTeachers = $teachers->filter(function($teacher) use($start, $end, $start_time, $end_time, $weekday) {
            
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                // Only check dates that match the target weekday
                if (strtolower($date->format('l')) === $weekday) {
                    if (!$teacher->isAvailableAt($date->toDateString(), $start_time, $end_time)) {
                        return false; // Teacher unavailable on this specific weekday/time → exclude
                    }
                }
            }
            
            return true; // Teacher available for all instances of the weekday in range
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
    
    
}