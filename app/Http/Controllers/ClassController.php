<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_classes')->only(['index', 'show']);
        $this->middleware('can:create_classes')->only(['create', 'store']);
        $this->middleware('can:edit_classes')->only(['edit', 'update']);
        $this->middleware('can:delete_classes')->only(['destroy']);
    }

    public function index(): View
    {
        $classes = SchoolClass::with(['grade'])
            ->withCount('sections')
            ->orderBy('grade_id')
            ->orderBy('name')
            ->paginate(15);

        return view('classes.index', compact('classes'));
    }

    public function create(): View
    {
        return view('classes.create', [
            'class' => new SchoolClass(),
            'grades' => Grade::orderBy('name')->get(),
            'sections' => collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $class = SchoolClass::create($data);

        $this->syncSections($class, $request);

        return redirect()
            ->route('classes.show', $class)
            ->with('success', 'تم إنشاء الفصل الدراسي بنجاح.');
    }

    public function show(SchoolClass $class): View
    {
        $class->load([
            'grade',
            'sections' => function ($query) {
                $query->withCount('timetables')->orderBy('name');
            },
        ]);

        return view('classes.show', [
            'class' => $class,
        ]);
    }

    public function edit(SchoolClass $class): View
    {
        $class->load(['sections' => function ($query) {
            $query->orderBy('name');
        }]);

        return view('classes.edit', [
            'class' => $class,
            'grades' => Grade::orderBy('name')->get(),
            'sections' => $class->sections,
        ]);
    }

    public function update(Request $request, SchoolClass $class): RedirectResponse
    {
        $data = $this->validated($request, $class->id);

        $class->update($data);

        $this->syncSections($class, $request);

        return redirect()
            ->route('classes.show', $class)
            ->with('success', 'تم تحديث بيانات الفصل الدراسي بنجاح.');
    }

    public function destroy(SchoolClass $class): RedirectResponse
    {
        $class->delete();

        return redirect()
            ->route('classes.index')
            ->with('success', 'تم حذف الفصل الدراسي بنجاح.');
    }

    protected function validated(Request $request, ?int $classId = null): array
    {
        return Arr::except($request->validate([
            'grade_id' => ['nullable', 'exists:grades,id'],
            'name' => ['required', 'string', 'max:120'],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('classes', 'code')->ignore($classId)],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:200'],
            'description' => ['nullable', 'string'],
            'sections' => ['sometimes', 'array'],
            'sections.*.id' => ['nullable', 'exists:sections,id'],
            'sections.*.name' => ['nullable', 'string', 'max:120'],
            'sections.*.code' => ['nullable', 'string', 'max:50'],
            'sections.*.description' => ['nullable', 'string'],
        ]), ['sections']);
    }

    protected function syncSections(SchoolClass $class, Request $request): void
    {
        if (! $request->has('sections')) {
            return;
        }

        $sections = collect($request->input('sections', []))
            ->map(function ($section) {
                return [
                    'id' => $section['id'] ?? null,
                    'name' => isset($section['name']) ? trim($section['name']) : null,
                    'code' => isset($section['code']) ? trim($section['code']) : null,
                    'description' => isset($section['description']) ? trim($section['description']) : null,
                ];
            })
            ->filter(function ($section) {
                return filled($section['name']);
            });

        if ($sections->isEmpty()) {
            $class->sections()->delete();
            return;
        }

        $keptIds = [];

        foreach ($sections as $section) {
            if ($section['id']) {
                $existing = $class->sections()->whereKey($section['id'])->first();

                if ($existing) {
                    $existing->update([
                        'name' => $section['name'],
                        'code' => $section['code'] ?: null,
                        'description' => $section['description'] ?: null,
                    ]);
                    $keptIds[] = $existing->id;
                    continue;
                }
            }

            $newSection = $class->sections()->create([
                'name' => $section['name'],
                'code' => $section['code'] ?: null,
                'description' => $section['description'] ?: null,
            ]);

            $keptIds[] = $newSection->id;
        }

        $class->sections()->whereNotIn('id', $keptIds)->delete();
    }
}
