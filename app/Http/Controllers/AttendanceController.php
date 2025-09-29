<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $records = AttendanceRecord::with(['enrollment.student', 'enrollment.classroom'])
            ->when($request->filled('classroom_id'), function ($query) use ($request) {
                $query->whereHas('enrollment', fn ($q) => $q->where('classroom_id', $request->integer('classroom_id')));
            })
            ->when($request->filled('student_id'), function ($query) use ($request) {
                $query->whereHas('enrollment', fn ($q) => $q->where('student_id', $request->integer('student_id')));
            })
            ->when($request->filled('from'), fn ($query) => $query->whereDate('attendance_date', '>=', request('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('attendance_date', '<=', request('to')))
            ->orderByDesc('attendance_date')
            ->paginate($request->integer('per_page', 20))->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($records);
        }

        return view('admin.attendance.index', [
            'attendanceRecords' => $records,
            'students' => Student::orderBy('last_name')->orderBy('first_name')->get(),
            'classrooms' => Classroom::orderBy('grade_level')->orderBy('name')->get(),
            'enrollments' => Enrollment::with(['student', 'classroom'])->get(),
            'filters' => [
                'classroom_id' => $request->input('classroom_id'),
                'student_id' => $request->input('student_id'),
                'from' => $request->input('from'),
                'to' => $request->input('to'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'enrollment_id' => ['required', 'exists:enrollments,id'],
            'attendance_date' => ['required', 'date'],
            'status' => ['required', 'in:present,absent,late,excused'],
            'remarks' => ['nullable', 'string'],
        ]);

        $record = AttendanceRecord::updateOrCreate(
            ['enrollment_id' => $data['enrollment_id'], 'attendance_date' => $data['attendance_date']],
            ['status' => $data['status'], 'remarks' => $data['remarks'] ?? null]
        );

        if ($request->wantsJson()) {
            return response()->json($record->load(['enrollment.student', 'enrollment.classroom']), Response::HTTP_CREATED);
        }

        return redirect()
            ->route('attendance.index')
            ->with('success', 'تم تسجيل الحضور بنجاح.');
    }

    public function show(Request $request, AttendanceRecord $attendance)
    {
        if ($request->wantsJson()) {
            return response()->json($attendance->load(['enrollment.student', 'enrollment.classroom']));
        }

        return view('admin.attendance.show', [
            'attendance' => $attendance->load(['enrollment.student', 'enrollment.classroom']),
        ]);
    }

    public function update(Request $request, AttendanceRecord $attendance)
    {
        $data = $request->validate([
            'status' => ['sometimes', 'required', 'in:present,absent,late,excused'],
            'remarks' => ['nullable', 'string'],
        ]);

        $attendance->update($data);

        if ($request->wantsJson()) {
            return response()->json($attendance->refresh()->load(['enrollment.student', 'enrollment.classroom']));
        }

        return redirect()
            ->route('attendance.index')
            ->with('success', 'تم تحديث سجل الحضور بنجاح.');
    }

    public function destroy(Request $request, AttendanceRecord $attendance)
    {
        $attendance->delete();

        if ($request->wantsJson()) {
            return response()->json(status: Response::HTTP_NO_CONTENT);
        }

        return redirect()
            ->route('attendance.index')
            ->with('success', 'تم حذف سجل الحضور بنجاح.');
    }
}
