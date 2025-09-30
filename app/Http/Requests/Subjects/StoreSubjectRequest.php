<?php

namespace App\Http\Requests\Subjects;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create_subjects') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150', 'unique:subjects,name'],
            'code' => ['nullable', 'string', 'max:50', 'unique:subjects,code'],
            'grade_level' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'teacher_ids' => ['nullable', 'array'],
            'teacher_ids.*' => ['integer', 'exists:users,id'],
            'classroom_ids' => ['nullable', 'array'],
            'classroom_ids.*' => ['integer', 'exists:classrooms,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'اسم المادة',
            'code' => 'كود المادة',
            'grade_level' => 'الصف الدراسي',
            'description' => 'الوصف',
            'teacher_ids' => 'المعلمين',
            'classroom_ids' => 'الفصول الدراسية',
        ];
    }
}
