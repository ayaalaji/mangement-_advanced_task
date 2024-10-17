<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  Auth::check() && Auth::user()->role=='admin';
    }
    public function prepareForValidation()
    {
        $this->merge([
            'name' =>ucwords($this->input('name')),
            'role' =>ucwords($this->input('role')),
            'manages_type' =>ucwords($this->input('manages_type')),
           
        ]);
        
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'email'=>['nullable','string','email','max:255', 
                Rule::unique('users')->ignore($this->route('user'))
            ],
            'password' => 'nullable|min:6|nullable'  ,
            'role' =>'nullable|string' ,
            'manages_type' =>'nullable|string|in:bug,feature|improvement'
        ];
    }
}
