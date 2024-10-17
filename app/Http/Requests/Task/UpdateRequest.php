<?php

namespace App\Http\Requests\Task;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->role=='admin' || Auth::user()->role=='user');
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
            'title' =>'nullable|string|min:3|max:100' ,
            'description' =>'nullable|string|min:5|max:255' ,
            'type' => 'nullable|in:bug,feature,improvement' ,
            'priority' =>'nullable|in:low,medium,high' ,
            'status' =>'nullable|in:open,in progress,completed,blocked' ,
            'due_date' =>'nullable|date_format:Y-m-d',
            'assigned_to' =>'nullable|exists:users,id|integer' ,
        ];
    }
}
