<?php

namespace App\Http\Requests\Subjects;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('edit_subjects') ?? false;
    }

    public function rules(): array
    {
        $subjectId = $this->route('subject')?->id ?? $this->route('subject');

        return [
            'name' => ['required', 'string', 'max:150', Rule::unique('subjects', 'name')->ignore($subjectId)],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('subjects', 'code')->ignore($subjectId)],
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
