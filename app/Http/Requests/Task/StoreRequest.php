<?php

namespace App\Http\Requests\Task;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role=='admin';
    }

    public function prepareForValidation()
    {
        $this->merge([
            'title' =>ucwords($this->input('title')),
            'description' =>ucwords($this->input('description')),
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
            'title' =>'required|string|min:3|max:100' ,
            'description' =>'required|string|min:5|max:255' ,
            'type' => 'required|string|in:bug,feature,improvement' ,
            'priority' =>'required|string|in:low,medium,high' ,
            'due_date' =>'required|date_format:Y-m-d',
            'assigned_to' =>'required|exists:users,id|integer' ,
        ];
    }
}
