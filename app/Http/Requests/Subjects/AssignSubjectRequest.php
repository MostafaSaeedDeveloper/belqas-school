<?php

namespace App\Http\Requests\Subjects;

use Illuminate\Foundation\Http\FormRequest;

class AssignSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('edit_subjects') ?? false;
    }

    public function rules(): array
    {
        return [
            'classroom_id' => ['required', 'integer', 'exists:classrooms,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'classroom_id' => 'الفصل الدراسي',
            'subject_id' => 'المادة الدراسية',
            'teacher_id' => 'المعلم',
        ];
    }
}
