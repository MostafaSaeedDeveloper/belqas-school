<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\ExamSchedule;
use App\Models\GradeRecord;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view_exams')->only(['index', 'show']);
        $this->middleware('can:create_exams')->only(['create', 'store']);
        $this->middleware('can:edit_exams')->only(['edit', 'update']);
        $this->middleware('can:delete_exams')->only(['destroy']);
    }

    public function index(Request $request): View
    {
        $classrooms = Classroom::query()->orderBy('name')->get();
        $subjects = Subject::query()->orderBy('name')->get();

        $query = ExamSchedule::query()->with(['classroom', 'subject', 'supervisor']);

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->integer('classroom_id'));
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->integer('subject_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('exam_date', '>=', Carbon::parse($request->input('date_from')));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('exam_date', '<=', Carbon::parse($request->input('date_to')));
        }

        $exams = $query->orderBy('exam_date')->paginate(15);

        return view('exams.index', [
            'exams' => $exams,
            'classrooms' => $classrooms,
            'subjects' => $subjects,
            'filters' => $request->only(['classroom_id', 'subject_id', 'status', 'date_from', 'date_to']),
        ]);
    }

    public function create(): View
    {
        return view('exams.create', [
            'classrooms' => Classroom::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => User::role('teacher')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'term' => 'nullable|string|max:100',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        ExamSchedule::create($validated);

        return redirect()->route('exams.index')->with('success', 'تم إنشاء الامتحان بنجاح.');
    }

    public function show(ExamSchedule $exam): View
    {
        $exam->load(['classroom.students', 'subject', 'supervisor']);

        $gradeSummary = GradeRecord::query()
            ->select(
                DB::raw('AVG(score / max_score * 100) as average'),
                DB::raw('MAX(score / max_score * 100) as best'),
                DB::raw('MIN(score / max_score * 100) as worst')
            )
            ->where('exam_schedule_id', $exam->id)
            ->first();

        $topStudents = GradeRecord::query()
            ->with('student')
            ->where('exam_schedule_id', $exam->id)
            ->orderByRaw('score / max_score DESC')
            ->limit(5)
            ->get();

        return view('exams.show', [
            'exam' => $exam,
            'gradeSummary' => $gradeSummary,
            'topStudents' => $topStudents,
        ]);
    }

    public function edit(ExamSchedule $exam): View
    {
        return view('exams.edit', [
            'exam' => $exam,
            'classrooms' => Classroom::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => User::role('teacher')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, ExamSchedule $exam): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'term' => 'nullable|string|max:100',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $exam->update($validated);

        return redirect()->route('exams.show', $exam)->with('success', 'تم تحديث بيانات الامتحان.');
    }

    public function destroy(ExamSchedule $exam): RedirectResponse
    {
        $exam->delete();

        return redirect()->route('exams.index')->with('success', 'تم حذف الامتحان بنجاح.');
    }
}
