<?php

namespace App\Http\Controllers\SchoolClasses;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolClasses\StoreClassRequest;
use App\Http\Requests\SchoolClasses\UpdateClassRequest;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request): View|ViewFactory
    {
        $classes = SchoolClass::query()
            ->with('homeroomTeacher')
            ->latest()
            ->filter($request->only(['search', 'grade_level']))
            ->paginate()
            ->withQueryString();

        return view('classes.index', [
            'classes' => $classes,
            'filters' => $request->only(['search', 'grade_level']),
            'teachers' => Teacher::orderBy('name')->get(),
        ]);
    }

    public function create(): View|ViewFactory
    {
        return view('classes.create', [
            'class' => new SchoolClass(),
            'teachers' => Teacher::orderBy('name')->get(),
        ]);
    }

    public function store(StoreClassRequest $request): RedirectResponse
    {
        $class = SchoolClass::create($request->validated());

        return redirect()->route('classes.show', $class)->with('success', 'تم إنشاء الفصل بنجاح.');
    }

    public function show(SchoolClass $class): View|ViewFactory
    {
        $class->load('homeroomTeacher');

        return view('classes.show', [
            'class' => $class,
        ]);
    }

    public function edit(SchoolClass $class): View|ViewFactory
    {
        return view('classes.edit', [
            'class' => $class,
            'teachers' => Teacher::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateClassRequest $request, SchoolClass $class): RedirectResponse
    {
        $class->update($request->validated());

        return redirect()->route('classes.show', $class)->with('success', 'تم تحديث بيانات الفصل.');
    }

    public function destroy(SchoolClass $class): RedirectResponse
    {
        $class->delete();

        return redirect()->route('classes.index')->with('success', 'تم حذف الفصل.');
    }
}
