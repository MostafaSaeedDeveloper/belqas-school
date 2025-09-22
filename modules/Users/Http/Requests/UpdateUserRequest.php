<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('edit_users') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $userId],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'exists:roles,name'],
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
            'name' => 'اسم المستخدم',
            'username' => 'اسم الدخول',
            'email' => 'البريد الإلكتروني',
            'phone' => 'رقم الهاتف',
            'password' => 'كلمة المرور',
            'role' => 'الدور',
            'avatar' => 'الصورة الشخصية',
        ];
    }
}
