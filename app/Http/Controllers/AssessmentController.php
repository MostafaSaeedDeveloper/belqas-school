<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $assessments = Assessment::with(['subject', 'classroom'])
            ->when($request->filled('classroom_id'), fn ($query) => $query->where('classroom_id', $request->integer('classroom_id')))
            ->when($request->filled('subject_id'), fn ($query) => $query->where('subject_id', $request->integer('subject_id')))
            ->orderByDesc('assessment_date')
            ->paginate($request->integer('per_page', 15))->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($assessments);
        }

        return view('admin.assessments.index', [
            'assessments' => $assessments,
            'subjects' => Subject::orderBy('name')->get(),
            'classrooms' => Classroom::orderBy('grade_level')->orderBy('name')->get(),
            'filters' => [
                'classroom_id' => $request->input('classroom_id'),
                'subject_id' => $request->input('subject_id'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'name' => ['required', 'string', 'max:255'],
            'assessment_date' => ['required', 'date'],
            'max_score' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        $assessment = Assessment::create($data);

        if ($request->wantsJson()) {
            return response()->json($assessment->load(['subject', 'classroom']), Response::HTTP_CREATED);
        }

        return redirect()
            ->route('assessments.index')
            ->with('success', 'تم تسجيل التقييم بنجاح.');
    }

    public function show(Request $request, Assessment $assessment)
    {
        if ($request->wantsJson()) {
            return response()->json($assessment->load(['subject', 'classroom', 'grades.enrollment.student']));
        }

        return view('admin.assessments.show', [
            'assessment' => $assessment->load(['subject', 'classroom', 'grades.enrollment.student']),
        ]);
    }

    public function update(Request $request, Assessment $assessment)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'assessment_date' => ['sometimes', 'required', 'date'],
            'max_score' => ['sometimes', 'required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        $assessment->update($data);

        if ($request->wantsJson()) {
            return response()->json($assessment->refresh()->load(['subject', 'classroom']));
        }

        return redirect()
            ->route('assessments.index')
            ->with('success', 'تم تحديث بيانات التقييم بنجاح.');
    }

    public function destroy(Request $request, Assessment $assessment)
    {
        $assessment->delete();

        if ($request->wantsJson()) {
            return response()->json(status: Response::HTTP_NO_CONTENT);
        }

        return redirect()
            ->route('assessments.index')
            ->with('success', 'تم حذف التقييم بنجاح.');
    }
}
