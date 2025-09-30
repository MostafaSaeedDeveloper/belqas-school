<?php

namespace Modules\Students\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create_students') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'active' => ['sometimes', 'boolean'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'student_code' => ['nullable', 'string', 'max:50', 'unique:student_profiles,student_code'],
            'gender' => ['nullable', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'grade_level' => ['nullable', 'string', 'max:120'],
            'classroom_id' => ['nullable', 'integer', 'exists:classrooms,id'],
            'enrollment_date' => ['nullable', 'date'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Custom attributes in Arabic.
     */
    public function attributes(): array
    {
        return [
            'name' => 'اسم الطالب',
            'username' => 'اسم المستخدم',
            'email' => 'البريد الإلكتروني',
            'phone' => 'رقم الهاتف',
            'password' => 'كلمة المرور',
            'student_code' => 'الرقم التعريفي للطالب',
            'gender' => 'النوع',
            'date_of_birth' => 'تاريخ الميلاد',
            'grade_level' => 'الصف الدراسي',
            'classroom_id' => 'الفصل',
            'enrollment_date' => 'تاريخ الالتحاق',
            'guardian_name' => 'اسم ولي الأمر',
            'guardian_phone' => 'هاتف ولي الأمر',
            'address' => 'العنوان',
            'notes' => 'الملاحظات',
        ];
    }
}
