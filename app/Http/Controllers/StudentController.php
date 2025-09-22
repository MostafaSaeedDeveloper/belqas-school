<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_students')->only(['index', 'show']);
        $this->middleware('can:create_students')->only(['create', 'store']);
        $this->middleware('can:edit_students')->only(['edit', 'update']);
        $this->middleware('can:delete_students')->only(['destroy']);
    }

    public function index(): View
    {
        $students = Student::with(['grade', 'classRoom', 'section'])
            ->latest('created_at')
            ->paginate(15);

        return view('students.index', compact('students'));
    }

    public function create(): View
    {
        return view('students.create', [
            'student' => new Student(['status' => 'enrolled']),
            'statuses' => Student::STATUSES,
        ] + $this->formLookups());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo_path'] = $request->file('profile_photo')->store('students', 'public');
        }

        $student = Student::create($data);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'تم إنشاء ملف الطالب بنجاح.');
    }

    public function show(Student $student): View
    {
        $student->load(['grade', 'classRoom', 'section', 'user']);

        return view('students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        $student->load(['grade', 'classRoom', 'section', 'user']);

        return view('students.edit', [
            'student' => $student,
            'statuses' => Student::STATUSES,
        ] + $this->formLookups());
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $data = $this->validatedData($request, $student->id);

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo_path'] = $request->file('profile_photo')->store('students', 'public');
        }

        $student->update($data);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'تم تحديث بيانات الطالب بنجاح.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'تم حذف ملف الطالب بنجاح.');
    }

    public function reports(): View
    {
        return view('students.reports');
    }

    protected function validatedData(Request $request, ?int $studentId = null): array
    {
        $uniqueStudentRule = 'required|string|max:50|unique:students,student_id';
        $uniqueAdmissionRule = 'nullable|string|max:50|unique:students,admission_number';

        if ($studentId) {
            $uniqueStudentRule .= ',' . $studentId;
            $uniqueAdmissionRule .= ',' . $studentId;
        }

        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'student_id' => [$uniqueStudentRule],
            'admission_number' => [$uniqueAdmissionRule],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'full_name' => ['nullable', 'string', 'max:200'],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date'],
            'national_id' => ['nullable', 'string', 'max:100'],
            'passport_number' => ['nullable', 'string', 'max:100'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'blood_type' => ['nullable', 'string', 'max:3'],
            'religion' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:120'],
            'guardian_name' => ['nullable', 'string', 'max:150'],
            'guardian_relation' => ['nullable', 'string', 'max:100'],
            'guardian_phone' => ['nullable', 'string', 'max:50'],
            'guardian_email' => ['nullable', 'email', 'max:150'],
            'guardian_address' => ['nullable', 'string', 'max:255'],
            'admission_date' => ['nullable', 'date'],
            'grade_id' => ['nullable', 'exists:grades,id'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'roll_number' => ['nullable', 'string', 'max:50'],
            'academic_year' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:' . implode(',', array_keys(Student::STATUSES))],
            'medical_info' => ['nullable', 'string'],
            'transportation' => ['nullable', 'string', 'max:120'],
            'previous_school' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if (empty($validated['full_name'])) {
            $validated['full_name'] = trim($validated['first_name'] . ' ' . $validated['last_name']);
        }

        return Arr::except($validated, ['profile_photo']);
    }

    protected function formLookups(): array
    {
        return [
            'grades' => Grade::orderBy('name')->get(),
            'classes' => SchoolClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'studentUsers' => User::role('student')->orderBy('name')->get(),
        ];
    }
}
