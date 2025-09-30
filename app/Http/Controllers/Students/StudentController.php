<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Http\Requests\Students\StoreStudentRequest;
use App\Http\Requests\Students\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index(Request $request): View
    {
        $filters = $this->filters($request);
        $students = $this->filteredStudents($filters);

        return view('students.index', [
            'students' => $students,
            'filters' => $filters,
            'stats' => $this->studentStatistics(),
            'grades' => $this->availableGrades(),
            'enrollmentYears' => $this->availableEnrollmentYears(),
            'statusOptions' => Student::statusOptions(),
            'genderOptions' => Student::genderOptions(),
        ]);
    }

    /**
     * Show the form for creating a new student.
     */
    public function create(): View
    {
        return view('students.create', $this->formOptions());
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $payload = Student::preparePayload($request->validated());
        $payload['student_code'] = $payload['student_code'] ?: Student::generateStudentCode();
        $payload['created_by'] = $request->user()?->id;
        $payload['updated_by'] = $request->user()?->id;

        $student = Student::create($payload);

        return Redirect::route('students.show', $student)
            ->with('success', 'تم إضافة الطالب بنجاح إلى قاعدة البيانات.');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student): View
    {
        return view('students.show', [
            'student' => $student,
            'statusOptions' => Student::statusOptions(),
        ]);
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student): View
    {
        return view('students.edit', array_merge($this->formOptions(), [
            'student' => $student,
        ]));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $payload = Student::preparePayload($request->validated());

        if (empty($payload['student_code'])) {
            unset($payload['student_code']);
        }

        $payload['updated_by'] = $request->user()?->id;

        $student->update($payload);

        return Redirect::route('students.show', $student)
            ->with('success', 'تم تحديث بيانات الطالب بنجاح.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return Redirect::route('students.index')
            ->with('success', 'تم حذف سجل الطالب بنجاح.');
    }

    /**
     * Display analytics and summary reports for students.
     */
    public function reports(Request $request): View
    {
        return view('students.reports', [
            'statistics' => $this->studentStatistics(),
            'gradeDistribution' => $this->gradeDistribution(),
            'statusDistribution' => $this->statusDistribution(),
            'genderDistribution' => $this->genderDistribution(),
            'recentStudents' => $this->recentStudents(),
            'topOutstandingBalances' => $this->topOutstandingBalances(),
        ]);
    }

    /**
     * Retrieve filtered students for the index page.
     */
    protected function filteredStudents(array $filters): LengthAwarePaginator
    {
        return Student::query()
            ->filter($filters)
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * Extract filters from the incoming request.
     */
    protected function filters(Request $request): array
    {
        return [
            'search' => trim((string) $request->input('search')) ?: null,
            'grade_level' => $request->input('grade_level') ?: null,
            'status' => $request->input('status') ?: null,
            'gender' => $request->input('gender') ?: null,
            'enrollment_year' => $request->input('enrollment_year') ?: null,
        ];
    }

    /**
     * Retrieve statistics used across the module.
     */
    protected function studentStatistics(): array
    {
        return [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
            'male' => Student::where('gender', 'male')->count(),
            'female' => Student::where('gender', 'female')->count(),
        ];
    }

    /**
     * Retrieve the distinct grade levels stored in the system.
     */
    protected function availableGrades(): SupportCollection
    {
        return Student::query()
            ->select('grade_level')
            ->whereNotNull('grade_level')
            ->distinct()
            ->orderBy('grade_level')
            ->pluck('grade_level');
    }

    /**
     * Retrieve the distinct enrollment years available.
     */
    protected function availableEnrollmentYears(): SupportCollection
    {
        return Student::query()
            ->whereNotNull('enrollment_date')
            ->selectRaw('DISTINCT YEAR(enrollment_date) AS enrollment_year')
            ->orderBy('enrollment_year', 'desc')
            ->pluck('enrollment_year');
    }

    /**
     * Retrieve view data shared by create/edit forms.
     */
    protected function formOptions(): array
    {
        return [
            'statusOptions' => Student::statusOptions(),
            'genderOptions' => Student::genderOptions(),
            'grades' => $this->availableGrades(),
        ];
    }

    /**
     * Retrieve distribution of students per grade.
     */
    protected function gradeDistribution(): SupportCollection
    {
        return Student::query()
            ->select('grade_level', DB::raw('COUNT(*) as total'))
            ->groupBy('grade_level')
            ->orderBy('grade_level')
            ->get()
            ->map(function ($row) {
                return [
                    'grade_level' => $row->grade_level ?? 'غير محدد',
                    'total' => (int) $row->total,
                ];
            });
    }

    /**
     * Retrieve distribution of students by status.
     */
    protected function statusDistribution(): SupportCollection
    {
        return Student::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderBy('status')
            ->get()
            ->map(function ($row) {
                $label = Student::statusOptions()[$row->status] ?? $row->status;

                return [
                    'status' => $row->status,
                    'label' => $label,
                    'total' => (int) $row->total,
                ];
            });
    }

    /**
     * Retrieve distribution of students by gender.
     */
    protected function genderDistribution(): SupportCollection
    {
        return Student::query()
            ->select('gender', DB::raw('COUNT(*) as total'))
            ->groupBy('gender')
            ->orderBy('gender')
            ->get()
            ->map(function ($row) {
                $label = Student::genderOptions()[$row->gender] ?? 'غير محدد';

                return [
                    'gender' => $row->gender,
                    'label' => $label,
                    'total' => (int) $row->total,
                ];
            });
    }

    /**
     * Retrieve the most recently registered students.
     */
    protected function recentStudents(int $limit = 8): Collection
    {
        return Student::query()
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Retrieve students with the highest outstanding balances.
     */
    protected function topOutstandingBalances(int $limit = 5): Collection
    {
        return Student::query()
            ->where('outstanding_fees', '>', 0)
            ->orderByDesc('outstanding_fees')
            ->take($limit)
            ->get();
    }
}
