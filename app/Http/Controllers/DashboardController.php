<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $stats = [
            'students' => Student::count(),
            'teachers' => Teacher::count(),
            'classrooms' => Classroom::count(),
            'assessments' => Assessment::count(),
            'grades_recorded' => Grade::count(),
        ];

        $recentStudents = Student::latest()->take(5)->get();
        $recentGrades = Grade::with(['enrollment.student', 'assessment.subject'])
            ->latest('graded_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentStudents', 'recentGrades'));
    }
}
