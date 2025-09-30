<?php

namespace Modules\Teachers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Teachers\Http\Requests\StoreTeacherRequest;
use Modules\Teachers\Http\Requests\UpdateTeacherRequest;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index(Request $request): View
    {
        $filters = $this->filters($request);
        $teachers = $this->filteredTeachers($filters);

        $stats = [
            'total' => User::role('teacher')->count(),
            'active' => User::role('teacher')->where('active', true)->count(),
            'inactive' => User::role('teacher')->where('active', false)->count(),
            'with_subjects' => TeacherProfile::whereNotNull('subjects')->count(),
        ];

        return view('teachers::index', [
            'teachers' => $teachers,
            'filters' => $filters,
            'stats' => $stats,
            'specializations' => $this->specializationOptions(),
        ]);
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create(): View
    {
        return view('teachers::create', [
            'specializations' => $this->specializationOptions(),
        ]);
    }

    /**
     * Store a newly created teacher.
     */
    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $avatarPath = $this->handleAvatarUpload($request);

        $teacher = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'avatar' => $avatarPath,
            'active' => $request->boolean('active', true),
        ]);

        $teacher->assignRole('teacher');

        $teacher->teacherProfile()->create($this->profilePayload($data));

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', 'تم إضافة المعلم بنجاح.');
    }

    /**
     * Display the specified teacher.
     */
    public function show(User $teacher): View
    {
        $teacher->load('teacherProfile');

        return view('teachers::show', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(User $teacher): View
    {
        $teacher->load('teacherProfile');

        return view('teachers::edit', [
            'teacher' => $teacher,
            'specializations' => $this->specializationOptions(),
        ]);
    }

    /**
     * Update the specified teacher.
     */
    public function update(UpdateTeacherRequest $request, User $teacher): RedirectResponse
    {
        $data = $request->validated();
        $payload = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'active' => $request->boolean('active', true),
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $avatarPath = $this->handleAvatarUpload($request, $teacher);
        if ($avatarPath !== null) {
            $payload['avatar'] = $avatarPath;
        }

        $teacher->update($payload);
        $teacher->teacherProfile()->updateOrCreate([], $this->profilePayload($data));

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', 'تم تحديث بيانات المعلم بنجاح.');
    }

    /**
     * Remove the specified teacher.
     */
    public function destroy(User $teacher): RedirectResponse
    {
        if ($teacher->is(auth()->user())) {
            return redirect()
                ->route('teachers.index')
                ->with('error', 'لا يمكنك حذف حسابك الشخصي.');
        }

        $this->deleteAvatarIfExists($teacher);
        $teacher->delete();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'تم حذف المعلم بنجاح.');
    }

    /**
     * Display teachers schedules summary.
     */
    public function schedules(): View
    {
        $teachers = User::role('teacher')
            ->with('teacherProfile')
            ->whereHas('teacherProfile')
            ->orderBy('name')
            ->get();

        $bySpecialization = $teachers->groupBy(fn (User $teacher) => $teacher->teacherProfile?->specialization ?? 'غير محدد')
            ->map->count();

        $subjectsCloud = $teachers->flatMap(function (User $teacher) {
            return collect($teacher->teacherProfile?->subjects ?? []);
        })->filter()->countBy()->sortDesc();

        return view('teachers::schedules', compact('teachers', 'bySpecialization', 'subjectsCloud'));
    }

    /**
     * Retrieve filtered teachers.
     */
    protected function filteredTeachers(array $filters)
    {
        $query = User::query()
            ->role('teacher')
            ->with('teacherProfile');

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('email', 'like', "%{$filters['search']}%")
                    ->orWhere('username', 'like', "%{$filters['search']}%")
                    ->orWhere('phone', 'like', "%{$filters['search']}%")
                    ->orWhereHas('teacherProfile', function ($sub) use ($filters) {
                        $sub->where('teacher_code', 'like', "%{$filters['search']}%")
                            ->orWhere('specialization', 'like', "%{$filters['search']}%")
                            ->orWhere('qualification', 'like', "%{$filters['search']}%");
                    });
            });
        }

        if (! empty($filters['specialization'])) {
            $query->whereHas('teacherProfile', function ($q) use ($filters) {
                $q->where('specialization', $filters['specialization']);
            });
        }

        if (! empty($filters['gender'])) {
            $query->whereHas('teacherProfile', function ($q) use ($filters) {
                $q->where('gender', $filters['gender']);
            });
        }

        if ($filters['status'] !== null) {
            $query->where('active', (bool) $filters['status']);
        }

        return $query->orderBy('name')->paginate(12)->withQueryString();
    }

    /**
     * Extract filters from request.
     */
    protected function filters(Request $request): array
    {
        $status = match ($request->input('status')) {
            'active' => 1,
            'inactive' => 0,
            default => null,
        };

        return [
            'search' => $request->input('search'),
            'specialization' => $request->input('specialization'),
            'gender' => $request->input('gender'),
            'status' => $status,
        ];
    }

    /**
     * Prepare profile payload.
     */
    protected function profilePayload(array $data): array
    {
        $payload = Arr::only($data, [
            'teacher_code',
            'gender',
            'specialization',
            'qualification',
            'hire_date',
            'experience_years',
            'phone_secondary',
            'address',
            'office_hours',
            'notes',
        ]);

        $payload['subjects'] = $this->subjectsArray($data['subjects'] ?? null);

        return $payload;
    }

    /**
     * Convert subjects string to array.
     */
    protected function subjectsArray(?string $subjects): array
    {
        return collect(explode(',', $subjects ?? ''))
            ->map(fn ($subject) => trim($subject))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Handle avatar upload.
     */
    protected function handleAvatarUpload(Request $request, ?User $teacher = null): ?string
    {
        if (! $request->hasFile('avatar')) {
            return $teacher?->avatar;
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        if ($teacher && $teacher->avatar) {
            Storage::disk('public')->delete($teacher->avatar);
        }

        return $path;
    }

    /**
     * Delete stored avatar if exists.
     */
    protected function deleteAvatarIfExists(User $teacher): void
    {
        if ($teacher->avatar) {
            Storage::disk('public')->delete($teacher->avatar);
        }
    }

    /**
     * Specialization options list.
     */
    protected function specializationOptions(): array
    {
        return [
            'اللغة العربية',
            'اللغة الإنجليزية',
            'الرياضيات',
            'العلوم',
            'الدراسات الاجتماعية',
            'الحاسب الآلي',
            'اللغة الفرنسية',
            'اللغة الألمانية',
            'التربية الرياضية',
            'التربية الفنية',
            'التربية الدينية',
            'الموسيقى',
        ];
    }
}
