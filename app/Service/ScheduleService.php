<?php

namespace App\Service;

use Carbon\Carbon;
use Zap\Facades\Zap;

class ScheduleService
{
    /**
     * Create a new class instance.
     */
    public function availableTeachers($teachers,$start_date,$end_date,$start_time,$end_time)
    {
        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);
        $availableTeachers = $teachers->filter(function($teacher) use($start,$end,$start_time,$end_time){
            
             for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
           
            
                if (! $teacher->isAvailableAt($date->toDateString(), $start_time, $end_time)) {
                    return false;  // Teacher unavailable on this day/time slot → exclude
                }
            
                }
                return true;  // Teacher available for entire range
            })->values();
            
        return $availableTeachers;

    } 
    
}