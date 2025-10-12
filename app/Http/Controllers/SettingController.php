<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view_settings')->only(['general']);
        $this->middleware('can:edit_settings')->only(['updateGeneral']);
        $this->middleware('can:view_users')->only(['permissions']);
        $this->middleware('can:edit_users')->only(['updateRolePermissions']);
    }

    public function general(): View
    {
        $settings = [
            'school_name' => SchoolSetting::getValue('general.school_name', config('app.name')),
            'academic_year' => SchoolSetting::getValue('general.academic_year', now()->format('Y')),
            'contact_email' => SchoolSetting::getValue('general.contact_email'),
            'contact_phone' => SchoolSetting::getValue('general.contact_phone'),
            'school_address' => SchoolSetting::getValue('general.school_address'),
            'attendance_cutoff' => SchoolSetting::getValue('general.attendance_cutoff', '08:00'),
        ];

        return view('settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'school_address' => 'nullable|string|max:255',
            'attendance_cutoff' => 'required|date_format:H:i',
        ]);

        SchoolSetting::setValue('general.school_name', $validated['school_name']);
        SchoolSetting::setValue('general.academic_year', $validated['academic_year']);
        SchoolSetting::setValue('general.contact_email', $validated['contact_email']);
        SchoolSetting::setValue('general.contact_phone', $validated['contact_phone']);
        SchoolSetting::setValue('general.school_address', $validated['school_address']);
        SchoolSetting::setValue('general.attendance_cutoff', $validated['attendance_cutoff']);

        Cache::forget('school_settings');

        return redirect()->route('settings.general')->with('success', 'تم تحديث الإعدادات العامة بنجاح.');
    }

    public function permissions(Request $request): View
    {
        $roles = Role::query()->with('permissions')->orderBy('name')->get();
        $selectedRole = $roles->firstWhere('id', $request->integer('role_id')) ?? $roles->first();

        $permissions = Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(function ($permission) {
                return explode('_', $permission->name)[0];
            });

        return view('settings.permissions', [
            'roles' => $roles,
            'selectedRole' => $selectedRole,
            'permissions' => $permissions,
        ]);
    }

    public function updateRolePermissions(Request $request, Role $role): RedirectResponse
    {
        if ($role->name === 'super_admin') {
            return redirect()->route('settings.permissions', ['role_id' => $role->id])
                ->with('error', 'لا يمكن تعديل صلاحيات المدير العام.');
        }

        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('settings.permissions', ['role_id' => $role->id])
            ->with('success', 'تم تحديث صلاحيات الدور بنجاح.');
    }
}
