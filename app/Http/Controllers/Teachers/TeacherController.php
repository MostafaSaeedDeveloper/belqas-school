<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teachers\StoreTeacherRequest;
use App\Http\Requests\Teachers\UpdateTeacherRequest;
use App\Models\Teacher;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request): View|ViewFactory
    {
        $teachers = Teacher::query()
            ->latest()
            ->filter($request->only(['search', 'status']))
            ->paginate()
            ->withQueryString();

        return view('teachers.index', [
            'teachers' => $teachers,
            'filters' => $request->only(['search', 'status']),
            'statuses' => Teacher::STATUSES,
        ]);
    }

    public function create(): View|ViewFactory
    {
        return view('teachers.create', [
            'teacher' => new Teacher([
                'status' => 'active',
                'employee_code' => Teacher::generateEmployeeCode(),
            ]),
            'statuses' => Teacher::STATUSES,
        ]);
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $teacher = Teacher::create($request->validated());

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', 'تم إضافة المعلم بنجاح.');
    }

    public function show(Teacher $teacher): View|ViewFactory
    {
        return view('teachers.show', [
            'teacher' => $teacher,
        ]);
    }

    public function edit(Teacher $teacher): View|ViewFactory
    {
        return view('teachers.edit', [
            'teacher' => $teacher,
            'statuses' => Teacher::STATUSES,
        ]);
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $teacher->update($request->validated());

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', 'تم تحديث بيانات المعلم.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $teacher->delete();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'تم حذف المعلم.');
    }
}
