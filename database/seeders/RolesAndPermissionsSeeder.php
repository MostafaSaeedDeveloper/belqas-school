<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Contracts\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::truncate();
        Permission::truncate();

        // 📌 الصلاحيات
        $permissions = [
            // مستخدمين
            ['name' => 'manage users', 'display_name' => 'إدارة المستخدمين'],
            ['name' => 'view users',   'display_name' => 'عرض المستخدمين'],

            // طلاب
            ['name' => 'manage students', 'display_name' => 'إدارة الطلاب'],
            ['name' => 'view students',   'display_name' => 'عرض الطلاب'],

            // معلمين
            ['name' => 'manage teachers', 'display_name' => 'إدارة المعلمين'],
            ['name' => 'view teachers',   'display_name' => 'عرض المعلمين'],

            // فصول
            ['name' => 'manage classes', 'display_name' => 'إدارة الفصول'],
            ['name' => 'view classes',   'display_name' => 'عرض الفصول'],

            // مواد
            ['name' => 'manage subjects', 'display_name' => 'إدارة المواد'],
            ['name' => 'view subjects',   'display_name' => 'عرض المواد'],

            // جداول
            ['name' => 'manage timetable', 'display_name' => 'إدارة الجدول'],
            ['name' => 'view timetable',   'display_name' => 'عرض الجدول'],

            // حضور وغياب
            ['name' => 'manage attendance', 'display_name' => 'إدارة الحضور'],
            ['name' => 'view attendance',   'display_name' => 'عرض الحضور'],

            // امتحانات ودرجات
            ['name' => 'manage exams',  'display_name' => 'إدارة الامتحانات'],
            ['name' => 'view exams',    'display_name' => 'عرض الامتحانات'],
            ['name' => 'manage grades', 'display_name' => 'إدارة الدرجات'],
            ['name' => 'view grades',   'display_name' => 'عرض الدرجات'],

            // رسوم وفواتير
            ['name' => 'manage fees',     'display_name' => 'إدارة الرسوم'],
            ['name' => 'view fees',       'display_name' => 'عرض الرسوم'],
            ['name' => 'manage payments', 'display_name' => 'إدارة المدفوعات'],
            ['name' => 'view payments',   'display_name' => 'عرض المدفوعات'],

            // مكتبة
            ['name' => 'manage library', 'display_name' => 'إدارة المكتبة'],
            ['name' => 'view library',   'display_name' => 'عرض المكتبة'],

            // إشعارات ورسائل
            ['name' => 'manage notifications', 'display_name' => 'إدارة الإشعارات'],
            ['name' => 'view notifications',   'display_name' => 'عرض الإشعارات'],
            ['name' => 'send messages',        'display_name' => 'إرسال الرسائل'],
            ['name' => 'view messages',        'display_name' => 'عرض الرسائل'],
        ];

        foreach ($permissions as $perm) {
            Permission::create($perm);
        }

        // 📌 الأدوار
        $roles = [
            ['name' => 'admin',      'display_name' => 'مدير'],
            ['name' => 'teacher',    'display_name' => 'معلم'],
            ['name' => 'student',    'display_name' => 'طالب'],
            ['name' => 'parent',     'display_name' => 'ولي أمر'],
            ['name' => 'accountant', 'display_name' => 'محاسب'],
            ['name' => 'librarian',  'display_name' => 'أمين مكتبة'],
            ['name' => 'staff',      'display_name' => 'موظف إداري'],
        ];

        foreach ($roles as $roleData) {
            $role = Role::create($roleData);

            switch ($roleData['name']) {
                case 'admin':
                    $role->givePermissionTo(Permission::all());
                    break;

                case 'teacher':
                    $role->givePermissionTo([
                        'view students', 'manage attendance', 'manage grades',
                        'view timetable', 'view subjects'
                    ]);
                    break;

                case 'student':
                    $role->givePermissionTo([
                        'view grades', 'view attendance', 'view timetable', 'view subjects'
                    ]);
                    break;

                case 'parent':
                    $role->givePermissionTo([
                        'view students', 'view grades', 'view attendance', 'view fees'
                    ]);
                    break;

                case 'accountant':
                    $role->givePermissionTo([
                        'manage fees', 'view fees', 'manage payments', 'view payments', 'view students'
                    ]);
                    break;

                case 'librarian':
                    $role->givePermissionTo([
                        'manage library', 'view library', 'view students', 'view teachers'
                    ]);
                    break;

                case 'staff':
                    $role->givePermissionTo([
                        'view students', 'view teachers', 'view classes', 'view subjects', 'view timetable'
                    ]);
                    break;
            }
        }
    }
}
