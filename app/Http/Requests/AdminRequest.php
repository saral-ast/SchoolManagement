<?php

namespace App\Http\Requests;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
        ];
        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['email'] =  'required|string|email|max:255|unique:users,email';
        }

        if($this->isMethod('put')){
            $adminId = $this->route('admin')->user->id; 
            $rules['email'] =  'required|string|email|max:255|unique:users,email,'. $adminId;
   
        }
        return $rules;
    }
}