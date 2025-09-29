<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $enrollments = Enrollment::with(['student', 'classroom'])
            ->when($request->filled('classroom_id'), fn ($query) => $query->where('classroom_id', $request->integer('classroom_id')))
            ->when($request->filled('student_id'), fn ($query) => $query->where('student_id', $request->integer('student_id')))
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15))->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($enrollments);
        }

        return view('admin.enrollments.index', [
            'enrollments' => $enrollments,
            'students' => Student::orderBy('last_name')->orderBy('first_name')->get(),
            'classrooms' => Classroom::orderBy('grade_level')->orderBy('name')->get(),
            'filters' => [
                'classroom_id' => $request->input('classroom_id'),
                'student_id' => $request->input('student_id'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'enrolled_at' => ['nullable', 'date'],
            'active' => ['nullable', 'boolean'],
        ]);

        $enrollment = Enrollment::updateOrCreate(
            ['student_id' => $data['student_id'], 'classroom_id' => $data['classroom_id']],
            [
                'enrolled_at' => $data['enrolled_at'] ?? now()->toDateString(),
                'active' => $data['active'] ?? true,
            ]
        );

        if ($request->wantsJson()) {
            return response()->json($enrollment->load(['student', 'classroom']), Response::HTTP_CREATED);
        }

        return redirect()
            ->route('enrollments.index')
            ->with('success', 'تم حفظ بيانات القيد الدراسي بنجاح.');
    }

    public function show(Request $request, Enrollment $enrollment)
    {
        if ($request->wantsJson()) {
            return response()->json($enrollment->load(['student', 'classroom', 'grades.assessment']));
        }

        return view('admin.enrollments.show', [
            'enrollment' => $enrollment->load(['student', 'classroom', 'grades.assessment', 'attendanceRecords']),
        ]);
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $data = $request->validate([
            'enrolled_at' => ['nullable', 'date'],
            'active' => ['nullable', 'boolean'],
        ]);

        $enrollment->update($data);

        if ($request->wantsJson()) {
            return response()->json($enrollment->refresh()->load(['student', 'classroom']));
        }

        return redirect()
            ->route('enrollments.index')
            ->with('success', 'تم تحديث بيانات القيد الدراسي بنجاح.');
    }

    public function destroy(Request $request, Enrollment $enrollment)
    {
        $enrollment->delete();

        if ($request->wantsJson()) {
            return response()->json(status: Response::HTTP_NO_CONTENT);
        }

        return redirect()
            ->route('enrollments.index')
            ->with('success', 'تم حذف القيد الدراسي بنجاح.');
    }
}
