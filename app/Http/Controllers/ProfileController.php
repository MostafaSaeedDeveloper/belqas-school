<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request): View
    {
        $user = $request->user()->load(['roles', 'studentProfile', 'teacherProfile']);

        $permissions = $user->getAllPermissions()->pluck('display_name', 'name')->sort();

        return view('profile.show', [
            'user' => $user,
            'permissions' => $permissions,
        ]);
    }

    public function edit(Request $request): View
    {
        $user = $request->user()->load(['studentProfile', 'teacherProfile']);

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $payload = collect($validated)->only(['name', 'username', 'email', 'phone'])->toArray();

        if (! empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $payload['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($payload);

        if (array_key_exists('address', $validated)) {
            if ($user->studentProfile) {
                $user->studentProfile->update(['address' => $validated['address']]);
            }

            if ($user->teacherProfile) {
                $user->teacherProfile->update(['address' => $validated['address']]);
            }
        }

        return redirect()->route('profile.show')->with('success', 'تم تحديث ملفك الشخصي بنجاح.');
    }
}
