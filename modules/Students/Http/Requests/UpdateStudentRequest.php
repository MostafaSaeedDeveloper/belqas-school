<?php

namespace Modules\Students\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('edit_students') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $studentId = $this->route('student')?->getKey();

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $studentId],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $studentId],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'active' => ['nullable', 'boolean'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * Customize validation attributes.
     */
    public function attributes(): array
    {
        return [
            'name' => 'اسم الطالب',
            'username' => 'اسم الدخول',
            'email' => 'البريد الإلكتروني',
            'phone' => 'رقم الهاتف',
            'password' => 'كلمة المرور',
            'avatar' => 'صورة الطالب',
        ];
    }
}
