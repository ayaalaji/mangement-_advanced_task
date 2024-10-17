<?php

namespace App\Http\Requests\Attachement;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'manager';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file_path' => 'required|mimes:pdf,jpg,jpeg,png,docx|max:2048',
        ];
    }
    public function messages()
    {
        return [
            'file.required' => 'يجب رفع ملف',
            'task_id.exists' => 'نوع التاسك غير مطابق لاختصاص المدير',
        ];
    }
}
