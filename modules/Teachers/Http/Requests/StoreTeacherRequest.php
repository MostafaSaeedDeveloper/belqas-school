<?php

namespace Modules\Teachers\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create_teachers') ?? false;
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
            'teacher_code' => ['nullable', 'string', 'max:50', 'unique:teacher_profiles,teacher_code'],
            'gender' => ['nullable', 'in:male,female'],
            'specialization' => ['nullable', 'string', 'max:150'],
            'qualification' => ['nullable', 'string', 'max:150'],
            'hire_date' => ['nullable', 'date', 'before_or_equal:today'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:60'],
            'phone_secondary' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'subjects' => ['nullable', 'string', 'max:255'],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['integer', 'exists:subjects,id'],
            'office_hours' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'اسم المعلم',
            'username' => 'اسم المستخدم',
            'email' => 'البريد الإلكتروني',
            'phone' => 'رقم الهاتف',
            'password' => 'كلمة المرور',
            'teacher_code' => 'الرقم الوظيفي',
            'gender' => 'النوع',
            'specialization' => 'التخصص',
            'qualification' => 'المؤهل العلمي',
            'hire_date' => 'تاريخ التعيين',
            'experience_years' => 'سنوات الخبرة',
            'phone_secondary' => 'هاتف إضافي',
            'address' => 'العنوان',
            'subjects' => 'المواد الدراسية',
            'subject_ids' => 'المواد المسجلة',
            'office_hours' => 'ساعات التواجد',
            'notes' => 'الملاحظات',
        ];
    }
}
