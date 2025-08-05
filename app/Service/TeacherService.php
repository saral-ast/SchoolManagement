<?php

namespace App\Service;

use App\Models\Teacher;

class TeacherService
{
    /**
     * Create a new class instance.
     */
    public function create($data,$userId)
    {
         $teacher = Teacher::create([
                'user_id' => $userId,
                'qualification' => $data['qualification'],
                'joining_date' => $data['joining_date'],
            ]);
            
            $teacher->subjects()->attach($data['subject_ids']);
            return $teacher;
    }

    public function update($data,$teacher){
        $teacher->update([
            'qualification' => $data['qualification'],
            'joining_date' => $data['joining_date']
        ]);

        if (isset($data['subject_ids'])) {
            $teacher->subjects()->sync($data['subject_ids']);
        }

        return $teacher;
    }

    public function destroy($teacher){
        $teacher->subjects()->detach();
        return $teacher->delete();
    }
}