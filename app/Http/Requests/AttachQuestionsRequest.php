<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AttachQuestionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => 'required|in:draft,final',
            'question_ids' => 'nullable|array',
            'question_ids.*' => 'integer|exists:questions,id',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $action = (string) $this->input('action');
            if ($action !== 'final') {
                return;
            }

            $quiz = $this->route('quiz');
            if (!$quiz) {
                $validator->errors()->add('quiz', 'Quiz not found.');
                return;
            }

            $questionIds = (array) $this->input('question_ids', []);
            if (count($questionIds) === 0) {
                $validator->errors()->add('question_ids', 'Please select at least one question.');
                return;
            }

            $selectedCount = count($questionIds);
            $expectedCount = (int) $quiz->total_questions;
            if ($selectedCount !== $expectedCount) {
                $validator->errors()->add('question_ids', "Selected questions ($selectedCount) must equal required total questions ($expectedCount).");
            }

            $totalMarks = \App\Models\Question::whereIn('id', $questionIds)->sum('mark');
            $expectedMarks = (int) $quiz->total_marks;
            if ((int) $totalMarks !== $expectedMarks) {
                $validator->errors()->add('question_ids', "Sum of selected question marks ($totalMarks) must equal required total marks ($expectedMarks).");
            }
        });
    }
}


