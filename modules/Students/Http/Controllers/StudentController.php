<?php

namespace Modules\Students\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Students\Http\Requests\StoreStudentRequest;
use Modules\Students\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        $filters = $this->filters($request);
        $students = $this->filteredStudents($filters);

        $stats = [
            'total' => $this->studentsQuery()->count(),
            'active' => $this->studentsQuery()->where('active', true)->count(),
            'inactive' => $this->studentsQuery()->where('active', false)->count(),
        ];

        return view('students::index', compact('students', 'filters', 'stats'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('students::create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $avatarPath = $this->handleAvatarUpload($request);

        $student = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'avatar' => $avatarPath,
            'active' => $request->boolean('active', true),
        ]);

        $student->syncRoles(['student']);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'تم إضافة الطالب بنجاح.');
    }

    /**
     * Display the specified student.
     */
    public function show(User $student)
    {
        $student->load('roles', 'permissions');

        $assignedRole = $student->roles->first();
        $rolePermissions = $assignedRole?->permissions()->pluck('display_name', 'name') ?? collect();
        $activityCount = method_exists($student, 'activities') ? $student->activities()->count() : 0;

        return view('students::show', [
            'student' => $student,
            'rolePermissions' => $rolePermissions,
            'activityCount' => $activityCount,
        ]);
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(User $student)
    {
        return view('students::edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(UpdateStudentRequest $request, User $student): RedirectResponse
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

        $avatarPath = $this->handleAvatarUpload($request, $student);
        if ($avatarPath !== null) {
            $payload['avatar'] = $avatarPath;
        }

        $student->update($payload);
        $student->syncRoles(['student']);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'تم تحديث بيانات الطالب بنجاح.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(User $student): RedirectResponse
    {
        $this->deleteAvatarIfExists($student);
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'تم حذف الطالب بنجاح.');
    }

    /**
     * Toggle the activation status of the given student.
     */
    public function toggleStatus(User $student): RedirectResponse
    {
        $student->update(['active' => ! $student->active]);

        return redirect()
            ->back()
            ->with('success', $student->active ? 'تم تفعيل الطالب.' : 'تم إيقاف الطالب.');
    }

    /**
     * Reset the password of the given student.
     */
    public function resetPassword(User $student): RedirectResponse
    {
        $plainPassword = Str::random(12);
        $student->forceFill([
            'password' => Hash::make($plainPassword),
        ])->save();

        return redirect()
            ->back()
            ->with('info', 'تم إعادة تعيين كلمة المرور للطالب. كلمة المرور الجديدة: ' . $plainPassword);
    }

    /**
     * Display simple reports for students.
     */
    public function reports()
    {
        $statistics = [
            'total' => $this->studentsQuery()->count(),
            'active' => $this->studentsQuery()->where('active', true)->count(),
            'inactive' => $this->studentsQuery()->where('active', false)->count(),
        ];

        $recentStudents = $this->studentsQuery()
            ->latest()
            ->take(8)
            ->get();

        return view('students::reports', compact('statistics', 'recentStudents'));
    }

    /**
     * Retrieve the base query for students handled within the module.
     */
    protected function studentsQuery(): Builder
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'student'));
    }

    /**
     * Retrieve filtered students for the index page.
     */
    protected function filteredStudents(array $filters): LengthAwarePaginator
    {
        $query = $this->studentsQuery()->with('roles');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
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
            'status' => $status,
        ];
    }

    /**
     * Handle avatar upload for store/update operations.
     */
    protected function handleAvatarUpload(Request $request, ?User $student = null): ?string
    {
        if (! $request->hasFile('avatar')) {
            return $student?->avatar;
        }

        $avatar = $request->file('avatar');
        $path = $avatar->store('avatars', 'public');

        if ($student && $student->avatar) {
            Storage::disk('public')->delete($student->avatar);
        }

        return $path;
    }

    /**
     * Delete stored avatar if it exists.
     */
    protected function deleteAvatarIfExists(User $student): void
    {
        if ($student->avatar) {
            Storage::disk('public')->delete($student->avatar);
        }
    }
}
