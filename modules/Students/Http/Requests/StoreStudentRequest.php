<?php

namespace Modules\Students\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'student_code' => ['nullable', 'string', 'max:50', 'unique:students,student_code'],
            'name' => ['required', 'string', 'max:255'],
            'english_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', Rule::in(array_keys(Student::genderOptions()))],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'grade_level' => ['required', 'string', 'max:120'],
            'classroom' => ['nullable', 'string', 'max:120'],
            'enrollment_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(array_keys(Student::statusOptions()))],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_relationship' => ['nullable', 'string', 'max:120'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'transportation_method' => ['nullable', 'string', 'max:120'],
            'outstanding_fees' => ['nullable', 'numeric', 'min:0'],
            'medical_notes' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Sanitize the input before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('outstanding_fees')) {
            $this->merge([
                'outstanding_fees' => $this->convertLocalizedNumber($this->input('outstanding_fees')),
            ]);
        }
    }

    /**
     * Convert localized number inputs into float values.
     */
    protected function convertLocalizedNumber($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $normalized = str_replace([' ', '٬', ','], ['', '', '.'], $value);

        return is_numeric($normalized) ? (float) $normalized : null;
    }
}
