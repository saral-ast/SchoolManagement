<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParentRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'birth_date' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'relation' => 'required|string|max:255',
            'secondary_phone' => 'max:10',
            'class_ids' => 'required|array|min:1',
            'class_ids.*' => 'required|exists:classes,id',
            'student_id' => 'required|array|min:1',
            'student_id.*' => 'required|exists:students,id',
        ];

        if ($this->isMethod('put')) {
            $parentId = $this->route('parent')->user->id; 
            $rules['email'] = 'required|string|email|max:255|unique:users,email,'. $parentId;
        }
        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['email'] = 'required|string|email|max:255|unique:users,email';
        }
        return $rules;
    }
}