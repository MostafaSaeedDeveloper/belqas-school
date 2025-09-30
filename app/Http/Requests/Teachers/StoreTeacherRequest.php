<?php

namespace App\Http\Requests\Teachers;

use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_code' => ['nullable', 'string', 'max:20', 'unique:teachers,employee_code'],
            'name' => ['required', 'string', 'max:255'],
            'english_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:teachers,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'hire_date' => ['nullable', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(Teacher::STATUSES))],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function prepareForValidation(): void
    {
        if (! $this->filled('employee_code')) {
            $this->merge([
                'employee_code' => Teacher::generateEmployeeCode(),
            ]);
        }
    }
}
