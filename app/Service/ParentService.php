<?php

namespace App\Service;

use App\Models\StudentParent;

class ParentService
{
    /**
     * Create a new class instance.
     */
    public function create($data, $userId)
    {
        $parent = StudentParent::create([
            'user_id' => $userId,
            'occupation' => $data['occupation'],
            'relation' => $data['relation'],
            'secondary_phone' => $data['secondary_phone'],
            // 'student_id' => 1,
        ]);
        // dd($data);
        // Attach multiple students if provided
        if (isset($data['student_id']) && is_array($data['student_id'])) {
            $parent->students()->attach($data['student_id']);
        }

        return $parent;
    }

    public function update($data, $parent){
        $parent->update([
            'occupation' => $data['occupation'],
            'relation' => $data['relation'],
            'secondary_phone' => $data['secondary_phone'],
        ]);
        
        // Sync students (this will handle adding/removing relationships)
        if (isset($data['student_id']) && is_array($data['student_id'])) {
            $parent->students()->sync($data['student_id']);
        }
        
        return $parent;
    }
    
    public function destroy($parent){
        $parent->students()->detach();
        return $parent->delete();
    }
}