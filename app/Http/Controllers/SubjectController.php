<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::with(['teacher'])
            ->withCount(['classrooms'])
            ->when($request->filled('teacher_id'), fn ($query) => $query->where('teacher_id', $request->integer('teacher_id')))
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15))->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($subjects);
        }

        $teachers = Teacher::orderBy('last_name')->orderBy('first_name')->get();
        $classrooms = Classroom::orderBy('grade_level')->orderBy('name')->get();

        return view('admin.subjects.index', [
            'subjects' => $subjects,
            'teachers' => $teachers,
            'classrooms' => $classrooms,
            'filters' => [
                'teacher_id' => $request->input('teacher_id'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:subjects,code'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'description' => ['nullable', 'string'],
            'classroom_ids' => ['nullable', 'array'],
            'classroom_ids.*' => ['integer', 'exists:classrooms,id'],
        ]);

        $classroomIds = $data['classroom_ids'] ?? [];
        unset($data['classroom_ids']);

        $subject = DB::transaction(function () use ($data, $classroomIds) {
            $subject = Subject::create($data);
            if ($classroomIds) {
                $subject->classrooms()->sync($classroomIds);
            }
            return $subject;
        });

        if ($request->wantsJson()) {
            return response()->json($subject->load(['teacher', 'classrooms']), Response::HTTP_CREATED);
        }

        return redirect()
            ->route('subjects.index')
            ->with('success', 'تم إضافة المادة الدراسية بنجاح.');
    }

    public function show(Request $request, Subject $subject)
    {
        if ($request->wantsJson()) {
            return response()->json($subject->load(['teacher', 'classrooms']));
        }

        return view('admin.subjects.show', [
            'subject' => $subject->load(['teacher', 'classrooms.homeroomTeacher']),
        ]);
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:50', "unique:subjects,code,{$subject->id}"],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'description' => ['nullable', 'string'],
            'classroom_ids' => ['nullable', 'array'],
            'classroom_ids.*' => ['integer', 'exists:classrooms,id'],
        ]);

        $classroomIds = $data['classroom_ids'] ?? null;
        unset($data['classroom_ids']);

        DB::transaction(function () use ($subject, $data, $classroomIds) {
            $subject->update($data);
            if (! is_null($classroomIds)) {
                $subject->classrooms()->sync($classroomIds);
            }
        });

        if ($request->wantsJson()) {
            return response()->json($subject->refresh()->load(['teacher', 'classrooms']));
        }

        return redirect()
            ->route('subjects.index')
            ->with('success', 'تم تحديث بيانات المادة بنجاح.');
    }

    public function destroy(Request $request, Subject $subject)
    {
        $subject->delete();

        if ($request->wantsJson()) {
            return response()->json(status: Response::HTTP_NO_CONTENT);
        }

        return redirect()
            ->route('subjects.index')
            ->with('success', 'تم حذف المادة الدراسية بنجاح.');
    }
}
