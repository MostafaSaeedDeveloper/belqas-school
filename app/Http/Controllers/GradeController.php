<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $grades = Grade::with(['enrollment.student', 'enrollment.classroom', 'assessment.subject'])
            ->when($request->filled('classroom_id'), function ($query) use ($request) {
                $query->whereHas('enrollment', fn ($q) => $q->where('classroom_id', $request->integer('classroom_id')));
            })
            ->when($request->filled('student_id'), fn ($query) => $query->whereHas('enrollment', fn ($q) => $q->where('student_id', $request->integer('student_id'))))
            ->when($request->filled('assessment_id'), fn ($query) => $query->where('assessment_id', $request->integer('assessment_id')))
            ->orderByDesc('graded_at')
            ->paginate($request->integer('per_page', 15))->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($grades);
        }

        return view('admin.grades.index', [
            'grades' => $grades,
            'assessments' => Assessment::orderByDesc('assessment_date')->with('subject')->get(),
            'enrollments' => Enrollment::with(['student', 'classroom'])->orderByDesc('enrolled_at')->get(),
            'students' => Student::orderBy('last_name')->orderBy('first_name')->get(),
            'classrooms' => Classroom::orderBy('grade_level')->orderBy('name')->get(),
            'filters' => [
                'classroom_id' => $request->input('classroom_id'),
                'student_id' => $request->input('student_id'),
                'assessment_id' => $request->input('assessment_id'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'enrollment_id' => ['required', 'exists:enrollments,id'],
            'assessment_id' => ['required', 'exists:assessments,id'],
            'score' => ['required', 'integer', 'min:0'],
            'graded_at' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ]);

        $assessment = Assessment::findOrFail($data['assessment_id']);
        if ($data['score'] > $assessment->max_score) {
            $errorMessage = 'Score cannot exceed assessment maximum score.';

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['score' => $errorMessage]);
        }

        $grade = Grade::updateOrCreate(
            ['enrollment_id' => $data['enrollment_id'], 'assessment_id' => $data['assessment_id']],
            [
                'score' => $data['score'],
                'graded_at' => $data['graded_at'] ?? now()->toDateString(),
                'remarks' => $data['remarks'] ?? null,
            ]
        );

        if ($request->wantsJson()) {
            return response()->json($grade->load(['enrollment.student', 'assessment']), Response::HTTP_CREATED);
        }

        return redirect()
            ->route('grades.index')
            ->with('success', 'تم تسجيل الدرجة بنجاح.');
    }

    public function show(Request $request, Grade $grade)
    {
        if ($request->wantsJson()) {
            return response()->json($grade->load(['enrollment.student', 'enrollment.classroom', 'assessment.subject']));
        }

        return view('admin.grades.show', [
            'grade' => $grade->load(['enrollment.student', 'enrollment.classroom', 'assessment.subject']),
        ]);
    }

    public function update(Request $request, Grade $grade)
    {
        $data = $request->validate([
            'score' => ['sometimes', 'required', 'integer', 'min:0'],
            'graded_at' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ]);

        if (array_key_exists('score', $data)) {
            $assessment = $grade->assessment;
            if ($data['score'] > $assessment->max_score) {
                $errorMessage = 'Score cannot exceed assessment maximum score.';

                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => $errorMessage,
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['score' => $errorMessage]);
            }
        }

        $grade->update($data);

        if ($request->wantsJson()) {
            return response()->json($grade->refresh()->load(['enrollment.student', 'assessment.subject']));
        }

        return redirect()
            ->route('grades.index')
            ->with('success', 'تم تحديث بيانات الدرجة بنجاح.');
    }

    public function destroy(Request $request, Grade $grade)
    {
        $grade->delete();

        if ($request->wantsJson()) {
            return response()->json(status: Response::HTTP_NO_CONTENT);
        }

        return redirect()
            ->route('grades.index')
            ->with('success', 'تم حذف الدرجة بنجاح.');
    }
}
