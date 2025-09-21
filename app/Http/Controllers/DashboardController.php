<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view_dashboard');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        // إحصائيات بسيطة للوحة التحكم
        $stats = [
            'students' => User::role('student')->count(),
            'teachers' => User::role('teacher')->count(),
            'parents' => User::role('parent')->count(),
            'total_users' => User::count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
        ];

        // آخر المستخدمين المسجلين
        $recent_users = User::latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'role' => $user->getRoleNames()->first() ?? 'بدون دور',
                    'created_at' => $user->created_at->diffForHumans(),
                ];
            });

        return view('admin.dashboard', compact('stats', 'recent_users'));
    }
}
