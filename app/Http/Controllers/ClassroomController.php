<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $classrooms = Classroom::with(['homeroomTeacher'])
            ->withCount(['students'])
            ->orderBy('grade_level')
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15))->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($classrooms);
        }

        $teachers = Teacher::orderBy('last_name')->orderBy('first_name')->get();

        return view('admin.classrooms.index', [
            'classrooms' => $classrooms,
            'teachers' => $teachers,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'string', 'max:50'],
            'section' => ['nullable', 'string', 'max:50'],
            'room_number' => ['nullable', 'string', 'max:50'],
            'homeroom_teacher_id' => ['nullable', 'exists:teachers,id'],
        ]);

        $classroom = Classroom::create($data);

        if ($request->wantsJson()) {
            return response()->json($classroom->load('homeroomTeacher'), Response::HTTP_CREATED);
        }

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'تم إضافة الفصل الدراسي بنجاح.');
    }

    public function show(Request $request, Classroom $classroom)
    {
        if ($request->wantsJson()) {
            return response()->json($classroom->load(['homeroomTeacher', 'students', 'subjects']));
        }

        return view('admin.classrooms.show', [
            'classroom' => $classroom->load(['homeroomTeacher', 'students', 'subjects.teacher']),
        ]);
    }

    public function update(Request $request, Classroom $classroom)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'grade_level' => ['sometimes', 'required', 'string', 'max:50'],
            'section' => ['nullable', 'string', 'max:50'],
            'room_number' => ['nullable', 'string', 'max:50'],
            'homeroom_teacher_id' => ['nullable', 'exists:teachers,id'],
        ]);

        $classroom->update($data);

        if ($request->wantsJson()) {
            return response()->json($classroom->refresh()->load(['homeroomTeacher']));
        }

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'تم تحديث بيانات الفصل بنجاح.');
    }

    public function destroy(Request $request, Classroom $classroom)
    {
        $classroom->delete();

        if ($request->wantsJson()) {
            return response()->json(status: Response::HTTP_NO_CONTENT);
        }

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'تم حذف الفصل بنجاح.');
    }
}
