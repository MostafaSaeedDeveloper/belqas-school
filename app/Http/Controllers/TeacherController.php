<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $teachers = Teacher::with(['classrooms', 'subjects'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(function ($q) use ($term) {
                    $q->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->orderBy('last_name')
            ->paginate($request->integer('per_page', 15))->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($teachers);
        }

        return view('admin.teachers.index', [
            'teachers' => $teachers,
            'filters' => [
                'search' => $request->string('search')->toString(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:teachers,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'hire_date' => ['nullable', 'date'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $teacher = Teacher::create($data);

        if ($request->wantsJson()) {
            return response()->json($teacher, Response::HTTP_CREATED);
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', 'تم إضافة المعلم بنجاح.');
    }

    public function show(Request $request, Teacher $teacher)
    {
        if ($request->wantsJson()) {
            return response()->json($teacher->load(['classrooms', 'subjects']));
        }

        return view('admin.teachers.show', [
            'teacher' => $teacher->load(['classrooms', 'subjects']),
        ]);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', "unique:teachers,email,{$teacher->id}"],
            'phone' => ['nullable', 'string', 'max:50'],
            'hire_date' => ['nullable', 'date'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $teacher->update($data);

        if ($request->wantsJson()) {
            return response()->json($teacher->refresh()->load(['classrooms', 'subjects']));
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', 'تم تحديث بيانات المعلم بنجاح.');
    }

    public function destroy(Request $request, Teacher $teacher)
    {
        $teacher->delete();

        if ($request->wantsJson()) {
            return response()->json(status: Response::HTTP_NO_CONTENT);
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', 'تم حذف المعلم بنجاح.');
    }
}
