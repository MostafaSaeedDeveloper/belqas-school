<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $filters = $this->filters($request);
        $users = $this->filteredUsers($filters);

        $rolesQuery = Role::query();

        if (Schema::hasColumn('roles', 'display_name')) {
            $rolesQuery->orderBy('display_name');
        }

        $roles = $rolesQuery
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function (Role $role) {
                $displayName = $role->display_name ?: ucfirst(str_replace('_', ' ', $role->name));

                return [$role->name => $displayName];
            });

        $stats = [
            'total' => User::count(),
            'active' => User::where('active', true)->count(),
            'inactive' => User::where('active', false)->count(),
        ];

        return view('users.index', compact('users', 'filters', 'roles', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = $this->rolesForForms();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $avatarPath = $this->handleAvatarUpload($request);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'avatar' => $avatarPath,
            'active' => $request->boolean('active', true),
        ]);

        $user->syncRoles([$data['role']]);

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('roles', 'permissions');

        $assignedRole = $user->roles->first();
        $rolePermissions = $assignedRole?->permissions()->pluck('display_name', 'name') ?? collect();

        $activityCount = method_exists($user, 'activities') ? $user->activities()->count() : 0;

        return view('users.show', [
            'user' => $user,
            'rolePermissions' => $rolePermissions,
            'activityCount' => $activityCount,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = $this->rolesForForms();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $payload = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'active' => $request->boolean('active'),
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $avatarPath = $this->handleAvatarUpload($request, $user);
        if ($avatarPath !== null) {
            $payload['avatar'] = $avatarPath;
        }

        $user->update($payload);
        $user->syncRoles([$data['role']]);

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return redirect()
                ->route('users.index')
                ->with('error', 'لا يمكنك حذف حسابك الشخصي.');
        }

        $this->deleteAvatarIfExists($user);
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح.');
    }

    /**
     * Toggle the activation status of the given user.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return redirect()
                ->route('users.index')
                ->with('error', 'لا يمكنك تغيير حالة حسابك الشخصي.');
        }

        $user->update(['active' => ! $user->active]);

        return redirect()
            ->back()
            ->with('success', $user->active ? 'تم تفعيل المستخدم.' : 'تم إيقاف المستخدم.');
    }

    /**
     * Reset the password of the given user.
     */
    public function resetPassword(User $user): RedirectResponse
    {
        $plainPassword = Str::random(12);
        $user->forceFill([
            'password' => Hash::make($plainPassword),
        ])->save();

        return redirect()
            ->back()
            ->with('info', 'تم إعادة تعيين كلمة المرور بنجاح. كلمة المرور الجديدة: ' . $plainPassword);
    }

    /**
     * Retrieve filtered users for the index page.
     */
    protected function filteredUsers(array $filters): LengthAwarePaginator
    {
        $query = User::query()->with('roles');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        if ($filters['status'] !== null) {
            $query->where('active', (bool) $filters['status']);
        }

        return $query
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();
    }

    /**
     * Extract filters from the request.
     */
    protected function filters(Request $request): array
    {
        $status = $request->input('status');
        $status = $status === 'active' ? 1 : ($status === 'inactive' ? 0 : null);

        return [
            'search' => $request->input('search'),
            'role' => $request->input('role'),
            'status' => $status,
        ];
    }

    /**
     * Retrieve active roles for forms.
     */
    protected function rolesForForms()
    {
        $query = Role::query();

        if (Schema::hasColumn('roles', 'is_active')) {
            $query->where('is_active', true);
        }

        if (Schema::hasColumn('roles', 'display_name')) {
            $query->orderBy('display_name');
        }

        return $query
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function (Role $role) {
                return [$role->name => $role->display_name ?: ucfirst(str_replace('_', ' ', $role->name))];
            });
    }

    /**
     * Handle avatar upload for store/update operations.
     */
    protected function handleAvatarUpload(Request $request, ?User $user = null): ?string
    {
        if (! $request->hasFile('avatar')) {
            return $user?->avatar;
        }

        $avatar = $request->file('avatar');
        $path = $avatar->store('avatars', 'public');

        if ($user && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        return $path;
    }

    /**
     * Delete stored avatar if it exists.
     */
    protected function deleteAvatarIfExists(User $user): void
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
    }
}
