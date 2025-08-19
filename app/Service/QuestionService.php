<?php

namespace App\Service;

use App\Models\Question;

class QuestionService
{
    /**
     * Create a new class instance.
     */
    public function createQuestion($data)
    {
        $options = [
            'options' => $data['options'],
            'correct_option' => $data['correct_option'],
        ];
        Question::create([
            'question_text' => $data['question_text'],
            'subject_id' => $data['subject_id'],
            'options' => $options,
            'mark' => $data['mark'],
            'type' => $data['type'],
            'class_id' => $data['class_id'],
            'difficulty' => $data['difficulty'],
        ]);
        return true;
    }
}
