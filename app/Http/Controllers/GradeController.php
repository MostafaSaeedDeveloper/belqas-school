<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\ExamSchedule;
use App\Models\GradeRecord;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:create_grades')->only(['store']);
        $this->middleware('can:view_grades')->only(['input']);
        $this->middleware('can:grades_reports')->only(['reports']);
    }

    /**
     * Display grade input screen.
     */
    public function input(Request $request): View
    {
        $user = $request->user();

        $classrooms = $this->availableClassrooms($user);
        $subjects = $this->availableSubjects($user, $request->integer('classroom_id'));
        $exams = $this->availableExams($request->integer('classroom_id'), $request->integer('subject_id'));

        $selectedClassroom = $classrooms->firstWhere('id', $request->integer('classroom_id'));
        $selectedSubject = $subjects->firstWhere('id', $request->integer('subject_id'));
        $selectedExam = $exams->firstWhere('id', $request->integer('exam_id'));

        $students = collect();
        $records = collect();

        if ($selectedClassroom && $selectedSubject) {
            $students = $selectedClassroom->students()->with('studentProfile')->orderBy('name')->get();

            $records = GradeRecord::query()
                ->where('classroom_id', $selectedClassroom->id)
                ->where('subject_id', $selectedSubject->id)
                ->when($selectedExam, fn($query) => $query->where('exam_schedule_id', $selectedExam->id))
                ->get()
                ->keyBy('student_id');
        }

        return view('grades.input', [
            'classrooms' => $classrooms,
            'subjects' => $subjects,
            'exams' => $exams,
            'selectedClassroom' => $selectedClassroom,
            'selectedSubject' => $selectedSubject,
            'selectedExam' => $selectedExam,
            'students' => $students,
            'records' => $records,
            'assessmentTypes' => [
                'exam' => 'امتحان نهائي',
                'midterm' => 'اختبار نصفي',
                'quiz' => 'اختبار قصير',
                'assignment' => 'واجب',
            ],
        ]);
    }

    /**
     * Store grade records.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_id' => 'nullable|exists:exam_schedules,id',
            'assessment_type' => 'required|string|max:50',
            'grades' => 'required|array',
            'grades.*.score' => 'required|numeric|min:0',
            'grades.*.max_score' => 'required|numeric|min:1',
            'grades.*.notes' => 'nullable|string|max:500',
        ]);

        /** @var User $user */
        $user = $request->user();

        $classroom = Classroom::findOrFail($validated['classroom_id']);
        $this->authorizeClassroom($user, $classroom);

        $subject = Subject::findOrFail($validated['subject_id']);
        $this->authorizeSubject($user, $subject, $classroom);

        $exam = null;
        if (! empty($validated['exam_id'])) {
            $exam = ExamSchedule::findOrFail($validated['exam_id']);
        }

        $validStudentIds = $classroom->students()->pluck('users.id')->toArray();

        DB::transaction(function () use ($validated, $user, $classroom, $subject, $exam, $validStudentIds) {
            foreach ($validated['grades'] as $studentId => $payload) {
                $studentId = (int) $studentId;

                if (! in_array($studentId, $validStudentIds, true)) {
                    continue;
                }

                if (empty($payload['score']) && empty($payload['notes'])) {
                    continue;
                }

                $maxScore = $payload['max_score'];
                $score = min($payload['score'], $maxScore);
                $percentage = $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
                $gradeLetter = $this->mapPercentageToGrade($percentage);

                GradeRecord::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subject->id,
                        'classroom_id' => $classroom->id,
                        'assessment_type' => $validated['assessment_type'],
                        'exam_schedule_id' => optional($exam)->id,
                    ],
                    [
                        'recorded_by' => $user->id,
                        'score' => $score,
                        'max_score' => $maxScore,
                        'grade' => $gradeLetter,
                        'notes' => $payload['notes'] ?: null,
                    ]
                );
            }
        });

        return redirect()
            ->route('grades.input', [
                'classroom_id' => $classroom->id,
                'subject_id' => $subject->id,
                'exam_id' => optional($exam)->id,
                'assessment_type' => $validated['assessment_type'],
            ])
            ->with('success', 'تم حفظ الدرجات بنجاح.');
    }

    /**
     * Display grade reports and analytics.
     */
    public function reports(Request $request): View
    {
        $user = $request->user();
        $classrooms = $this->availableClassrooms($user);
        $subjects = Subject::query()->orderBy('name')->get();

        $selectedClassroom = $classrooms->firstWhere('id', $request->integer('classroom_id'));
        $selectedSubject = $subjects->firstWhere('id', $request->integer('subject_id'));

        $query = GradeRecord::query()->with(['student', 'subject', 'classroom']);

        if ($selectedClassroom) {
            $query->where('classroom_id', $selectedClassroom->id);
        } elseif ($classrooms->isNotEmpty()) {
            $query->whereIn('classroom_id', $classrooms->pluck('id'));
        }

        if ($selectedSubject) {
            $query->where('subject_id', $selectedSubject->id);
        }

        if ($assessmentType = $request->input('assessment_type')) {
            $query->where('assessment_type', $assessmentType);
        }

        $reportQuery = clone $query;

        $records = $query->latest()->paginate(25);

        $averageScore = (clone $reportQuery)->avg(DB::raw('score / max_score * 100'));
        $highestScore = (clone $reportQuery)->orderByRaw('score / max_score DESC')->first();
        $lowestScore = (clone $reportQuery)->orderByRaw('score / max_score ASC')->first();

        $distribution = (clone $reportQuery)
            ->select('grade', DB::raw('count(*) as total'))
            ->groupBy('grade')
            ->pluck('total', 'grade')
            ->sortKeysDesc();

        return view('grades.reports', [
            'classrooms' => $classrooms,
            'subjects' => $subjects,
            'selectedClassroom' => $selectedClassroom,
            'selectedSubject' => $selectedSubject,
            'records' => $records,
            'averageScore' => $averageScore,
            'highestScore' => $highestScore,
            'lowestScore' => $lowestScore,
            'distribution' => $distribution,
        ]);
    }

    protected function availableClassrooms(User $user)
    {
        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return Classroom::query()->orderBy('name')->get();
        }

        if ($user->can('create_grades')) {
            return Classroom::query()
                ->where(function ($query) use ($user) {
                    $query->where('homeroom_teacher_id', $user->id)
                        ->orWhereHas('teachers', function ($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                })
                ->orderBy('name')
                ->get();
        }

        return collect();
    }

    protected function availableSubjects(User $user, ?int $classroomId)
    {
        $query = Subject::query()->orderBy('name');

        if ($classroomId) {
            $query->whereHas('classrooms', fn($q) => $q->where('classrooms.id', $classroomId));
        }

        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return $query->get();
        }

        if ($user->can('create_grades')) {
            return $query
                ->whereHas('teachers', fn($q) => $q->where('users.id', $user->id))
                ->get();
        }

        return collect();
    }

    protected function availableExams(?int $classroomId, ?int $subjectId)
    {
        if (! $classroomId || ! $subjectId) {
            return collect();
        }

        return ExamSchedule::query()
            ->where('classroom_id', $classroomId)
            ->where('subject_id', $subjectId)
            ->orderByDesc('exam_date')
            ->get();
    }

    protected function authorizeClassroom(User $user, Classroom $classroom): void
    {
        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return;
        }

        $isTeacherOfClass = $classroom->teachers()->where('users.id', $user->id)->exists();
        $isHomeroomTeacher = $classroom->homeroom_teacher_id === $user->id;

        abort_unless($isTeacherOfClass || $isHomeroomTeacher, 403, 'غير مصرح لك بإدارة درجات هذا الفصل.');
    }

    protected function authorizeSubject(User $user, Subject $subject, Classroom $classroom): void
    {
        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return;
        }

        $teachesSubject = $subject->teachers()->where('users.id', $user->id)->exists();
        $teachesClass = $classroom->teachers()->where('users.id', $user->id)->exists();

        abort_unless($teachesSubject && $teachesClass, 403, 'غير مصرح لك بتسجيل الدرجات لهذه المادة.');
    }

    protected function mapPercentageToGrade(float $percentage): string
    {
        return match (true) {
            $percentage >= 90 => 'A',
            $percentage >= 80 => 'B',
            $percentage >= 70 => 'C',
            $percentage >= 60 => 'D',
            default => 'F',
        };
    }
}
