<?php

namespace App\Service;

use App\Models\Student;

class StudentService
{
    /**
     * Create a new class instance.
     */
    public function create($data,$userId)
    {
         return Student::create([
                'user_id' => $userId,
                'admission_number' => $data['admission_number'],
                'class_id' => $data['class_id'],
                'roll_number' => $data['roll_number'],
            ]);
    }

    public function update($student,$data){
       return  $student->update([
                'admission_number' => $data['admission_number'],
                'class_id' => $data['class_id'],
                'roll_number' => $data['roll_number'],
            ]);
    }

    public function destroy($student){
        return  $student->delete();
    }
}