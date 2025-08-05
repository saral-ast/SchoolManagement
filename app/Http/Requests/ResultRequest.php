<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResultRequest extends FormRequest
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
        $rules =  [
            
            'marks' => 'required|array|min:1',
            'marks.*' => 'required|array',
            'total_marks' => 'required|numeric|min:20',
            'obtained_marks' => 'required|numeric|min:0|max:' . request('total_marks'),
            'overall_grade' => 'required|string|in:A,B,C,D,E,F',
            'exam_date' => 'required|date|before_or_equal:today',
            'exam_type' => 'required|string|in:mid_term,final,test',
            'result_status' => 'required|string|in:pass,fail',
        ];

          if ($this->isMethod('post')) {
            $rules['class_id'] = 'required|exists:classes,id';
            $rules['student_id'] =  'required|exists:students,id';
        }
        return $rules;
    }
}