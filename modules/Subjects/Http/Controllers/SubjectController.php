<?php

namespace Modules\Subjects\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Subjects\Http\Requests\StoreSubjectRequest;
use Modules\Subjects\Http\Requests\UpdateSubjectRequest;

class SubjectController extends Controller
{
    public function index(Request $request): View|ViewFactory
    {
        $subjects = Subject::query()
            ->with('teacher')
            ->latest()
            ->filter($request->only(['search', 'grade_level']))
            ->paginate()
            ->withQueryString();

        return view('subjects::index', [
            'subjects' => $subjects,
            'filters' => $request->only(['search', 'grade_level']),
            'teachers' => Teacher::orderBy('name')->get(),
        ]);
    }

    public function create(): View|ViewFactory
    {
        return view('subjects::create', [
            'subject' => new Subject(),
            'teachers' => Teacher::orderBy('name')->get(),
        ]);
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        $subject = Subject::create($request->validated());

        return redirect()->route('subjects.show', $subject)->with('success', 'تم إنشاء المادة بنجاح.');
    }

    public function show(Subject $subject): View|ViewFactory
    {
        $subject->load('teacher');

        return view('subjects::show', [
            'subject' => $subject,
        ]);
    }

    public function edit(Subject $subject): View|ViewFactory
    {
        return view('subjects::edit', [
            'subject' => $subject,
            'teachers' => Teacher::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateSubjectRequest $request, Subject $subject): RedirectResponse
    {
        $subject->update($request->validated());

        return redirect()->route('subjects.show', $subject)->with('success', 'تم تحديث بيانات المادة.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'تم حذف المادة.');
    }
}
