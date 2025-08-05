<?php

namespace App\Service;

use App\Models\StudentParent;

class ParentService
{
    /**
     * Create a new class instance.
     */
    public function create($data,$userId)
    {
        return StudentParent::create([
            'user_id' => $userId,
            'student_id' => $data['student_id'],
            'occupation' => $data['occupation'],
            'relation' => $data['relation'],
            'secondary_phone' => $data['secondary_phone'],
        ]);
    }

    public function update($data,$parent){
          return $parent->update([
                'student_id' => $data['student_id'],
                'occupation' => $data['occupation'],
                'relation' => $data['relation'],
                'secondary_phone' => $data['secondary_phone'],
            ]);
    }
    
    public function destroy($parent){
        return  $parent->delete();
    }
}