<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subjects\AssignSubjectRequest;
use App\Http\Requests\Subjects\StoreSubjectRequest;
use App\Http\Requests\Subjects\UpdateSubjectRequest;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_subjects')->only(['index', 'show', 'assignments']);
        $this->middleware('can:create_subjects')->only(['create', 'store']);
        $this->middleware('can:edit_subjects')->only(['edit', 'update', 'storeAssignment']);
        $this->middleware('can:delete_subjects')->only(['destroy']);
    }

    /**
     * Display a listing of subjects.
     */
    public function index(): View
    {
        $subjects = Subject::withCount(['teachers', 'classrooms'])
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Subject::count(),
            'with_teachers' => Subject::has('teachers')->count(),
            'with_classes' => Subject::has('classrooms')->count(),
        ];

        return view('subjects.index', compact('subjects', 'stats'));
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create(): View
    {
        return view('subjects.create', [
            'subject' => new Subject(),
            'teachers' => $this->teacherOptions(),
            'classrooms' => $this->classroomOptions(),
            'gradeOptions' => $this->gradeOptions(),
        ]);
    }

    /**
     * Store a newly created subject.
     */
    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $subject = Subject::create([
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'grade_level' => $data['grade_level'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        $this->syncTeachers($subject, $data['teacher_ids'] ?? []);
        $this->syncClassrooms($subject, $data['classroom_ids'] ?? []);

        return redirect()
            ->route('subjects.show', $subject)
            ->with('success', 'تم إنشاء المادة الدراسية بنجاح.');
    }

    /**
     * Display the specified subject.
     */
    public function show(Subject $subject): View
    {
        $subject->load([
            'teachers.teacherProfile',
            'classrooms.homeroomTeacher',
            'classrooms.students',
        ]);

        $studentsCount = $subject->classrooms
            ->flatMap->students
            ->unique('id')
            ->count();

        return view('subjects.show', [
            'subject' => $subject,
            'studentsCount' => $studentsCount,
        ]);
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject): View
    {
        $subject->load(['teachers', 'classrooms']);

        return view('subjects.edit', [
            'subject' => $subject,
            'teachers' => $this->teacherOptions(),
            'classrooms' => $this->classroomOptions(),
            'gradeOptions' => $this->gradeOptions(),
        ]);
    }

    /**
     * Update the specified subject.
     */
    public function update(UpdateSubjectRequest $request, Subject $subject): RedirectResponse
    {
        $data = $request->validated();

        $subject->update([
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'grade_level' => $data['grade_level'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        $this->syncTeachers($subject, $data['teacher_ids'] ?? []);
        $this->syncClassrooms($subject, $data['classroom_ids'] ?? []);

        return redirect()
            ->route('subjects.show', $subject)
            ->with('success', 'تم تحديث بيانات المادة بنجاح.');
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with('success', 'تم حذف المادة الدراسية بنجاح.');
    }

    /**
     * Display assignments matrix for subjects and classrooms.
     */
    public function assignments(): View
    {
        $classrooms = Classroom::with([
            'subjects' => function ($query) {
                $query->orderBy('name');
            },
            'subjects.teachers',
            'homeroomTeacher',
        ])->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        return view('subjects.assignments', [
            'classrooms' => $classrooms,
            'teachers' => $this->teacherOptions(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a subject assignment for a classroom.
     */
    public function storeAssignment(AssignSubjectRequest $request): RedirectResponse
    {
        $classroom = Classroom::findOrFail($request->input('classroom_id'));
        $subjectId = (int) $request->input('subject_id');
        $teacherId = $request->filled('teacher_id') ? (int) $request->input('teacher_id') : null;

        if ($classroom->subjects()->where('subject_id', $subjectId)->exists()) {
            $classroom->subjects()->updateExistingPivot($subjectId, ['teacher_id' => $teacherId]);
        } else {
            $classroom->subjects()->attach($subjectId, ['teacher_id' => $teacherId]);
        }

        if ($teacherId) {
            if (! $classroom->teachers()->where('teacher_id', $teacherId)->exists()) {
                $classroom->teachers()->attach($teacherId);
            }

            $subject = Subject::find($subjectId);
            if ($subject && ! $subject->teachers()->where('teacher_id', $teacherId)->exists()) {
                $subject->teachers()->attach($teacherId);
            }
        }

        return redirect()
            ->back()
            ->with('success', 'تم تحديث تكليف المادة بنجاح.');
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
     * Available classrooms ordered by grade and name.
     */
    protected function classroomOptions()
    {
        return Classroom::orderBy('grade_level')
            ->orderBy('name')
            ->get();
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
     * Sync subject teachers relation.
     */
    protected function syncTeachers(Subject $subject, array $teacherIds): void
    {
        $ids = collect($teacherIds)->filter()->map(fn ($id) => (int) $id)->all();
        $subject->teachers()->sync($ids);
    }

    /**
     * Sync subject classrooms relation while preserving teacher assignments.
     */
    protected function syncClassrooms(Subject $subject, array $classroomIds): void
    {
        $ids = collect($classroomIds)->filter()->map(fn ($id) => (int) $id)->all();

        $existingAssignments = $subject->classrooms()->pluck('classroom_subject.teacher_id', 'classrooms.id');

        $payload = collect($ids)->mapWithKeys(function ($classroomId) use ($existingAssignments) {
            return [$classroomId => ['teacher_id' => $existingAssignments[$classroomId] ?? null]];
        })->all();

        $subject->classrooms()->sync($payload);
    }
}
