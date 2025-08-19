<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'total_questions' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'type' => 'required|in:random,mixed',
            'negative_marking_enabled' => 'required|boolean',
            'negative_marking_percent' => 'required|numeric|min:0|max:100',
            'questions' => 'required|array',
            'questions.*.question_id' => 'required|exists:questions,id',
        ];
    }
}
