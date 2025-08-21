<?php

namespace App\Service;

use App\Models\Quiz;

class QuizService
{
    /**
     * Create a new class instance.
     */
    public function create($data)
    {
        return Quiz::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'subject_id' => $data['subject_id'],
            'created_by' => auth()->user()->id,
            'class_id' => $data['class_id'],
            'total_questions' => $data['total_questions'],
            'total_marks' => $data['total_marks'],
            'type' => $data['type'],
            'negative_marking_enabled' => $data['negative_marking_enabled'] ?? false,
            'negative_marking_percent' => $data['negative_marking_percent'] ?? 0,
        ]);
    }
}
