<?php

namespace App\Http\Controllers;

use App\Http\Requests\Classrooms\StoreClassroomRequest;
use App\Http\Requests\Classrooms\UpdateClassroomRequest;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_classes')->only(['index', 'show']);
        $this->middleware('can:view_timetable')->only(['timetables']);
        $this->middleware('can:create_classes')->only(['create', 'store']);
        $this->middleware('can:edit_classes')->only(['edit', 'update']);
        $this->middleware('can:delete_classes')->only(['destroy']);
    }

    /**
     * Display a listing of classrooms.
     */
    public function index(Request $request): View
    {
        $classrooms = Classroom::withCount(['students', 'teachers', 'subjects'])
            ->with('homeroomTeacher')
            ->orderBy('grade_level')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Classroom::count(),
            'with_homeroom' => Classroom::whereNotNull('homeroom_teacher_id')->count(),
            'students' => DB::table('classroom_student')->count(),
            'subjects' => DB::table('classroom_subject')->count(),
        ];

        return view('classes.index', compact('classrooms', 'stats'));
    }

    /**
     * Show the form for creating a new classroom.
     */
    public function create(): View
    {
        return view('classes.create', [
            'classroom' => new Classroom(),
            'gradeOptions' => $this->gradeOptions(),
            'teachers' => $this->teacherOptions(),
            'students' => $this->studentOptions(),
            'subjects' => $this->subjectOptions(),
        ]);
    }

    /**
     * Store a newly created classroom.
     */
    public function store(StoreClassroomRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $classroom = Classroom::create([
            'name' => $data['name'],
            'grade_level' => $data['grade_level'],
            'section' => $data['section'] ?? null,
            'capacity' => $data['capacity'] ?? null,
            'homeroom_teacher_id' => $data['homeroom_teacher_id'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        $this->syncTeachers($classroom, $data['teacher_ids'] ?? []);
        $this->syncStudents($classroom, $data['student_ids'] ?? []);
        $this->syncSubjects($classroom, $data['subject_ids'] ?? []);

        return redirect()
            ->route('classes.show', $classroom)
            ->with('success', 'تم إنشاء الفصل الدراسي بنجاح.');
    }

    /**
     * Display the specified classroom.
     */
    public function show(Classroom $classroom): View
    {
        $classroom->load([
            'homeroomTeacher',
            'students.studentProfile',
            'teachers.teacherProfile',
            'subjects.teachers',
        ]);

        return view('classes.show', [
            'classroom' => $classroom,
        ]);
    }

    /**
     * Show the form for editing the specified classroom.
     */
    public function edit(Classroom $classroom): View
    {
        $classroom->load(['teachers', 'students', 'subjects']);

        return view('classes.edit', [
            'classroom' => $classroom,
            'gradeOptions' => $this->gradeOptions(),
            'teachers' => $this->teacherOptions(),
            'students' => $this->studentOptions(),
            'subjects' => $this->subjectOptions(),
        ]);
    }

    /**
     * Update the specified classroom.
     */
    public function update(UpdateClassroomRequest $request, Classroom $classroom): RedirectResponse
    {
        $data = $request->validated();

        $classroom->update([
            'name' => $data['name'],
            'grade_level' => $data['grade_level'],
            'section' => $data['section'] ?? null,
            'capacity' => $data['capacity'] ?? null,
            'homeroom_teacher_id' => $data['homeroom_teacher_id'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        $this->syncTeachers($classroom, $data['teacher_ids'] ?? []);
        $this->syncStudents($classroom, $data['student_ids'] ?? []);
        $this->syncSubjects($classroom, $data['subject_ids'] ?? []);

        return redirect()
            ->route('classes.show', $classroom)
            ->with('success', 'تم تحديث بيانات الفصل بنجاح.');
    }

    /**
     * Remove the specified classroom from storage.
     */
    public function destroy(Classroom $classroom): RedirectResponse
    {
        $classroom->delete();

        return redirect()
            ->route('classes.index')
            ->with('success', 'تم حذف الفصل الدراسي بنجاح.');
    }

    /**
     * Display classrooms timetables overview.
     */
    public function timetables(): View
    {
        $classrooms = Classroom::with([
            'subjects' => function ($query) {
                $query->orderBy('name');
            },
            'subjects.teachers',
            'homeroomTeacher',
            'students',
        ])->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        return view('classes.timetables', compact('classrooms'));
    }

    /**
     * Grade options list.
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
     * Available teachers ordered by name.
     */
    protected function teacherOptions()
    {
        return User::role('teacher')
            ->with('teacherProfile')
            ->orderBy('name')
            ->get();
    }

    /**
     * Available students ordered by name.
     */
    protected function studentOptions()
    {
        return User::role('student')
            ->with('studentProfile')
            ->orderBy('name')
            ->get();
    }

    /**
     * Available subjects ordered by name.
     */
    protected function subjectOptions()
    {
        return Subject::orderBy('name')->get();
    }

    /**
     * Sync classroom teachers relation.
     */
    protected function syncTeachers(Classroom $classroom, array $teacherIds): void
    {
        $ids = collect($teacherIds)->filter()->map(fn ($id) => (int) $id)->all();

        if ($classroom->homeroom_teacher_id && ! in_array($classroom->homeroom_teacher_id, $ids, true)) {
            $ids[] = (int) $classroom->homeroom_teacher_id;
        }

        $classroom->teachers()->sync($ids);
    }

    /**
     * Sync classroom students relation and update their profiles.
     */
    protected function syncStudents(Classroom $classroom, array $studentIds): void
    {
        $ids = collect($studentIds)->filter()->map(fn ($id) => (int) $id)->all();
        $previous = $classroom->students()->pluck('users.id')->all();

        $classroom->students()->sync($ids);

        $assignedStudents = User::whereIn('id', $ids)
            ->with('studentProfile')
            ->get();

        foreach ($assignedStudents as $student) {
            $student->studentProfile?->update([
                'classroom' => $classroom->name,
                'grade_level' => $classroom->grade_level,
            ]);
        }

        $removed = array_diff($previous, $ids);
        if (! empty($removed)) {
            $removedStudents = User::whereIn('id', $removed)
                ->with(['studentClassrooms', 'studentProfile'])
                ->get();

            foreach ($removedStudents as $student) {
                if ($student->studentClassrooms->isEmpty()) {
                    $student->studentProfile?->update(['classroom' => null]);
                }
            }
        }
    }

    /**
     * Sync classroom subjects relation preserving teacher assignments.
     */
    protected function syncSubjects(Classroom $classroom, array $subjectIds): void
    {
        $ids = collect($subjectIds)->filter()->map(fn ($id) => (int) $id)->all();

        $existingAssignments = $classroom->subjects()->pluck('classroom_subject.teacher_id', 'subjects.id');

        $payload = collect($ids)->mapWithKeys(function ($subjectId) use ($existingAssignments) {
            return [$subjectId => ['teacher_id' => $existingAssignments[$subjectId] ?? null]];
        })->all();

        $classroom->subjects()->sync($payload);
    }
}
