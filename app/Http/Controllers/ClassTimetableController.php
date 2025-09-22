<?php

namespace App\Http\Controllers;

use App\Models\ClassTimetable;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClassTimetableController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_timetable')->only('index');
        $this->middleware('can:edit_classes')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request): View
    {
        $classes = SchoolClass::with('grade')->orderBy('name')->get();
        $selectedClassId = (int) $request->input('class_id', $classes->first()?->id);
        $selectedClass = $classes->firstWhere('id', $selectedClassId);

        $dayOrderSql = "CASE day_of_week "
            . "WHEN 'saturday' THEN 1 "
            . "WHEN 'sunday' THEN 2 "
            . "WHEN 'monday' THEN 3 "
            . "WHEN 'tuesday' THEN 4 "
            . "WHEN 'wednesday' THEN 5 "
            . "WHEN 'thursday' THEN 6 "
            . "WHEN 'friday' THEN 7 "
            . "ELSE 8 END";

        $timetables = ClassTimetable::with(['classRoom.grade', 'section'])
            ->when($selectedClassId, fn ($query) => $query->where('class_id', $selectedClassId))
            ->orderByRaw($dayOrderSql)
            ->orderBy('period')
            ->get()
            ->groupBy('day_of_week');

        $sections = $selectedClass ? $selectedClass->sections()->orderBy('name')->get() : collect();

        if ($selectedClass) {
            $selectedClass->setRelation('sections', $sections);
        }

        return view('classes.timetables', [
            'classes' => $classes,
            'selectedClassId' => $selectedClassId,
            'selectedClass' => $selectedClass,
            'timetables' => $timetables,
            'sections' => $sections,
            'days' => ClassTimetable::days(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        ClassTimetable::create($data);

        return redirect()
            ->route('classes.timetables', ['class_id' => $data['class_id']])
            ->with('success', 'تم إضافة الحصة إلى الجدول الدراسي.');
    }

    public function update(Request $request, ClassTimetable $timetable): RedirectResponse
    {
        $data = $this->validated($request, $timetable);

        $timetable->update($data);

        return redirect()
            ->route('classes.timetables', ['class_id' => $data['class_id']])
            ->with('success', 'تم تحديث بيانات الحصة بنجاح.');
    }

    public function destroy(ClassTimetable $timetable): RedirectResponse
    {
        $classId = $timetable->class_id;

        $timetable->delete();

        return redirect()
            ->route('classes.timetables', ['class_id' => $classId])
            ->with('success', 'تم حذف الحصة من الجدول الدراسي.');
    }

    protected function validated(Request $request, ?ClassTimetable $timetable = null): array
    {
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');
        $dayOfWeek = $request->input('day_of_week');

        $uniqueSlotRule = Rule::unique('class_timetables')
            ->where(function ($query) use ($classId, $sectionId, $dayOfWeek, $request) {
                $query->where('class_id', $classId)
                    ->where('day_of_week', $dayOfWeek)
                    ->where('period', $request->input('period'));

                if ($sectionId) {
                    $query->where('section_id', $sectionId);
                } else {
                    $query->whereNull('section_id');
                }
            });

        if ($timetable) {
            $uniqueSlotRule->ignore($timetable->id);
        }

        return $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'day_of_week' => ['required', Rule::in(array_keys(ClassTimetable::days()))],
            'period' => ['required', 'integer', 'min:1', 'max:12', $uniqueSlotRule],
            'subject' => ['required', 'string', 'max:150'],
            'teacher_name' => ['nullable', 'string', 'max:150'],
            'room' => ['nullable', 'string', 'max:50'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
