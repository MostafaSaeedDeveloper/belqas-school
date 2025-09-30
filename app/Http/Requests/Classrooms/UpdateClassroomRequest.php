<?php

namespace App\Http\Requests\Classrooms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('edit_classes') ?? false;
    }

    public function rules(): array
    {
        $classroomId = $this->route('classroom')?->id ?? $this->route('classroom');

        return [
            'name' => ['required', 'string', 'max:120', Rule::unique('classrooms', 'name')->ignore($classroomId)],
            'grade_level' => ['required', 'string', 'max:150'],
            'section' => ['nullable', 'string', 'max:50'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'homeroom_teacher_id' => ['nullable', 'integer', 'exists:users,id'],
            'description' => ['nullable', 'string'],
            'teacher_ids' => ['nullable', 'array'],
            'teacher_ids.*' => ['integer', 'exists:users,id'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['integer', 'exists:users,id'],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['integer', 'exists:subjects,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'اسم الفصل',
            'grade_level' => 'الصف الدراسي',
            'section' => 'الشعبة',
            'capacity' => 'الطاقة الاستيعابية',
            'homeroom_teacher_id' => 'رائد الفصل',
            'description' => 'الوصف',
            'teacher_ids' => 'المعلمين',
            'student_ids' => 'الطلاب',
            'subject_ids' => 'المواد الدراسية',
        ];
    }
}
