<?php

namespace Modules\Students\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Students\Http\Requests\StoreStudentRequest;
use Modules\Students\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index(Request $request): View
    {
        $filters = $this->filters($request);
        $students = $this->filteredStudents($filters);

        $stats = [
            'total' => User::role('student')->count(),
            'active' => User::role('student')->where('active', true)->count(),
            'inactive' => User::role('student')->where('active', false)->count(),
            'with_guardian' => StudentProfile::whereNotNull('guardian_phone')->count(),
        ];

        $gradeOptions = $this->gradeOptions();
        $classrooms = $this->classroomsList();

        return view('students::index', compact('students', 'filters', 'stats', 'gradeOptions', 'classrooms'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create(): View
    {
        return view('students::create', [
            'gradeOptions' => $this->gradeOptions(),
            'classrooms' => $this->classroomsList(),
        ]);
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
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'avatar' => $avatarPath,
            'active' => $request->boolean('active', true),
        ]);

        $student->assignRole('student');

        $student->studentProfile()->create($this->profilePayload($data));

        $this->syncStudentClassroom($student, $data['classroom_id'] ?? null);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'تم تسجيل الطالب بنجاح.');
    }

    /**
     * Display the specified student.
     */
    public function show(User $student): View
    {
        $student->load(['studentProfile', 'studentClassrooms']);

        return view('students::show', [
            'student' => $student,
        ]);
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(User $student): View
    {
        $student->load(['studentProfile', 'studentClassrooms']);

        return view('students::edit', [
            'student' => $student,
            'gradeOptions' => $this->gradeOptions(),
            'classrooms' => $this->classroomsList(),
        ]);
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
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'active' => $request->boolean('active', true),
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $avatarPath = $this->handleAvatarUpload($request, $student);
        if ($avatarPath !== null) {
            $payload['avatar'] = $avatarPath;
        }

        $student->update($payload);
        $student->studentProfile()->updateOrCreate([], $this->profilePayload($data));

        $this->syncStudentClassroom($student, $data['classroom_id'] ?? null);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'تم تحديث بيانات الطالب بنجاح.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(User $student): RedirectResponse
    {
        if ($student->is(auth()->user())) {
            return redirect()
                ->route('students.index')
                ->with('error', 'لا يمكنك حذف حسابك الشخصي.');
        }

        $this->deleteAvatarIfExists($student);
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'تم حذف الطالب بنجاح.');
    }

    /**
     * Export students as CSV file.
     */
    public function export(Request $request)
    {
        $this->authorize('export_students');

        $filters = $this->filters($request);
        $students = $this->filteredStudents($filters, false);

        $filename = 'students-export-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($students) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'الاسم الكامل',
                'اسم المستخدم',
                'البريد الإلكتروني',
                'رقم الهاتف',
                'الصف الدراسي',
                'الفصل',
                'اسم ولي الأمر',
                'رقم ولي الأمر',
                'تاريخ الالتحاق',
                'الحالة',
            ]);

            $students->each(function (User $student) use ($handle) {
                $profile = $student->studentProfile;

                fputcsv($handle, [
                    $student->name,
                    $student->username,
                    $student->email,
                    $student->phone,
                    $profile?->grade_level,
                    $profile?->classroom,
                    $profile?->guardian_name,
                    $profile?->guardian_phone,
                    optional($profile?->enrollment_date)->format('Y-m-d'),
                    $student->active ? 'نشط' : 'غير نشط',
                ]);
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Display reports and statistics.
     */
    public function reports(): View
    {
        $gradeDistribution = StudentProfile::select('grade_level', DB::raw('COUNT(*) as total'))
            ->whereNotNull('grade_level')
            ->groupBy('grade_level')
            ->orderBy('grade_level')
            ->get();

        $genderDistribution = StudentProfile::select('gender', DB::raw('COUNT(*) as total'))
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->get();

        $enrollmentTrend = StudentProfile::selectRaw('DATE_FORMAT(enrollment_date, "%Y-%m") as month, COUNT(*) as total')
            ->whereNotNull('enrollment_date')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($row) {
                return [
                    'month' => Carbon::createFromFormat('Y-m', $row->month)->translatedFormat('F Y'),
                    'total' => $row->total,
                ];
            });

        return view('students::reports', compact('gradeDistribution', 'genderDistribution', 'enrollmentTrend'));
    }

    /**
     * Retrieve filtered students.
     */
    protected function filteredStudents(array $filters, bool $paginate = true)
    {
        $query = User::query()
            ->role('student')
            ->with(['studentProfile', 'studentClassrooms']);

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('email', 'like', "%{$filters['search']}%")
                    ->orWhere('username', 'like', "%{$filters['search']}%")
                    ->orWhere('phone', 'like', "%{$filters['search']}%")
                    ->orWhereHas('studentProfile', function ($sub) use ($filters) {
                        $sub->where('guardian_name', 'like', "%{$filters['search']}%")
                            ->orWhere('guardian_phone', 'like', "%{$filters['search']}%")
                            ->orWhere('student_code', 'like', "%{$filters['search']}%");
                    });
            });
        }

        if (! empty($filters['grade_level'])) {
            $query->whereHas('studentProfile', function ($q) use ($filters) {
                $q->where('grade_level', $filters['grade_level']);
            });
        }

        if (! empty($filters['classroom_id'])) {
            $query->whereHas('studentClassrooms', function ($q) use ($filters) {
                $q->where('classroom_id', $filters['classroom_id']);
            });
        }

        if (! empty($filters['gender'])) {
            $query->whereHas('studentProfile', function ($q) use ($filters) {
                $q->where('gender', $filters['gender']);
            });
        }

        if ($filters['status'] !== null) {
            $query->where('active', (bool) $filters['status']);
        }

        $query->orderBy('name');

        if ($paginate) {
            return $query->paginate(12)->withQueryString();
        }

        return $query->get();
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
            'grade_level' => $request->input('grade_level'),
            'classroom_id' => $request->input('classroom_id'),
            'gender' => $request->input('gender'),
            'status' => $status,
        ];
    }

    /**
     * Prepare profile payload from validated data.
     */
    protected function profilePayload(array $data): array
    {
        $payload = Arr::only($data, [
            'student_code',
            'gender',
            'date_of_birth',
            'grade_level',
            'enrollment_date',
            'guardian_name',
            'guardian_phone',
            'address',
            'notes',
        ]);

        $payload['classroom'] = null;

        if (! empty($data['classroom_id'])) {
            $classroom = Classroom::find($data['classroom_id']);
            if ($classroom) {
                $payload['classroom'] = $classroom->name;
                if (empty($payload['grade_level'])) {
                    $payload['grade_level'] = $classroom->grade_level;
                }
            }
        }

        return $payload;
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
     * Delete stored avatar if exists.
     */
    protected function deleteAvatarIfExists(User $student): void
    {
        if ($student->avatar) {
            Storage::disk('public')->delete($student->avatar);
        }
    }

    /**
     * Available grade options.
     */
    protected function gradeOptions(): array
    {
        return [
            'رياض الأطفال (KG1)',
            'رياض الأطفال (KG2)',
            'الصف الأول الابتدائي',
            'الصف الثاني الابتدائي',
            'الصف الثالث الابتدائي',
            'الصف الرابع الابتدائي',
            'الصف الخامس الابتدائي',
            'الصف السادس الابتدائي',
            'الصف الأول الإعدادي',
            'الصف الثاني الإعدادي',
            'الصف الثالث الإعدادي',
            'الصف الأول الثانوي',
            'الصف الثاني الثانوي',
            'الصف الثالث الثانوي',
        ];
    }

    /**
     * Retrieve available classrooms ordered by grade and name.
     */
    protected function classroomsList()
    {
        return Classroom::orderBy('grade_level')
            ->orderBy('name')
            ->get();
    }

    /**
     * Sync the student's classroom relation and profile details.
     */
    protected function syncStudentClassroom(User $student, ?int $classroomId): void
    {
        if ($classroomId) {
            $classroom = Classroom::find($classroomId);

            if ($classroom) {
                $student->studentClassrooms()->sync([$classroom->id]);
                $student->studentProfile?->update([
                    'classroom' => $classroom->name,
                    'grade_level' => $classroom->grade_level ?? $student->studentProfile?->grade_level,
                ]);
            }

            return;
        }

        $student->studentClassrooms()->sync([]);
        $student->studentProfile?->update(['classroom' => null]);
    }
}
