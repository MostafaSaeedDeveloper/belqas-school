<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with(['classroom.homeroomTeacher'])
            ->when($request->filled('classroom_id'), fn ($query) => $query->where('classroom_id', $request->integer('classroom_id')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = '%' . trim($request->string('search')) . '%';
                $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$term]);
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate($request->integer('per_page', 15))->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($students);
        }

        $classrooms = Classroom::orderBy('grade_level')->orderBy('name')->get();

        return view('admin.students.index', [
            'students' => $students,
            'classrooms' => $classrooms,
            'filters' => [
                'search' => $request->string('search')->toString(),
                'classroom_id' => $request->input('classroom_id'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female'],
            'birth_date' => ['nullable', 'date'],
            'admission_date' => ['nullable', 'date'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:50'],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $student = Student::create($data);

        if ($request->wantsJson()) {
            return response()->json($student->load('classroom'), ResponseAlias::HTTP_CREATED);
        }

        return redirect()
            ->route('students.index')
            ->with('success', 'تم إضافة الطالب بنجاح.');
    }

    public function show(Request $request, Student $student)
    {
        if ($request->wantsJson()) {
            return response()->json($student->load(['classroom', 'enrollments.classroom']));
        }

        return view('admin.students.show', [
            'student' => $student->load(['classroom', 'enrollments.classroom']),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female'],
            'birth_date' => ['nullable', 'date'],
            'admission_date' => ['nullable', 'date'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:50'],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $student->update($data);

        if ($request->wantsJson()) {
            return response()->json($student->refresh()->load('classroom'));
        }

        return redirect()
            ->route('students.index')
            ->with('success', 'تم تحديث بيانات الطالب بنجاح.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        if ($request->wantsJson()) {
            return response()->json(status: ResponseAlias::HTTP_NO_CONTENT);
        }

        return redirect()
            ->route('students.index')
            ->with('success', 'تم حذف الطالب بنجاح.');
    }
}
