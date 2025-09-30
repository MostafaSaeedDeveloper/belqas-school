<?php

namespace Modules\Teachers\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('edit_teachers') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $teacherId = $this->route('teacher')?->getKey();

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($teacherId)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($teacherId)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'active' => ['sometimes', 'boolean'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'teacher_code' => ['nullable', 'string', 'max:50', Rule::unique('teacher_profiles', 'teacher_code')->ignore($teacherId, 'user_id')],
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
        return (new StoreTeacherRequest())->attributes();
    }
}
