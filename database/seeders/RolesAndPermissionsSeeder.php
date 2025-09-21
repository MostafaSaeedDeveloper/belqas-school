<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // مسح الكاش
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('🚀 بدء إنشاء الأدوار والصلاحيات...');

        // إنشاء الصلاحيات
        $this->createPermissions();

        // إنشاء الأدوار
        $this->createRoles();

        $this->command->info('✅ تم الانتهاء بنجاح!');
    }

    /**
     * إنشاء الصلاحيات
     */
    private function createPermissions()
    {
        $this->command->info('📝 إنشاء الصلاحيات...');

        $permissions = [
            // لوحة التحكم
            'view_dashboard' => 'عرض لوحة التحكم',

            // المستخدمين
            'view_users' => 'عرض المستخدمين',
            'create_users' => 'إنشاء مستخدمين',
            'edit_users' => 'تعديل المستخدمين',
            'delete_users' => 'حذف المستخدمين',

            // الطلاب
            'view_students' => 'عرض الطلاب',
            'create_students' => 'إنشاء طلاب',
            'edit_students' => 'تعديل الطلاب',
            'delete_students' => 'حذف الطلاب',
            'export_students' => 'تصدير بيانات الطلاب',

            // المعلمين
            'view_teachers' => 'عرض المعلمين',
            'create_teachers' => 'إنشاء معلمين',
            'edit_teachers' => 'تعديل المعلمين',
            'delete_teachers' => 'حذف المعلمين',

            // الفصول
            'view_classes' => 'عرض الفصول',
            'create_classes' => 'إنشاء فصول',
            'edit_classes' => 'تعديل الفصول',
            'delete_classes' => 'حذف الفصول',

            // المواد الدراسية
            'view_subjects' => 'عرض المواد',
            'create_subjects' => 'إنشاء مواد',
            'edit_subjects' => 'تعديل المواد',
            'delete_subjects' => 'حذف المواد',

            // الجداول الدراسية
            'view_timetable' => 'عرض الجداول',
            'create_timetable' => 'إنشاء جداول',
            'edit_timetable' => 'تعديل الجداول',
            'delete_timetable' => 'حذف الجداول',

            // الحضور والغياب
            'view_attendance' => 'عرض الحضور',
            'create_attendance' => 'تسجيل الحضور',
            'edit_attendance' => 'تعديل الحضور',
            'delete_attendance' => 'حذف الحضور',
            'attendance_reports' => 'تقارير الحضور',

            // الامتحانات
            'view_exams' => 'عرض الامتحانات',
            'create_exams' => 'إنشاء امتحانات',
            'edit_exams' => 'تعديل الامتحانات',
            'delete_exams' => 'حذف الامتحانات',

            // الدرجات
            'view_grades' => 'عرض الدرجات',
            'create_grades' => 'إدخال الدرجات',
            'edit_grades' => 'تعديل الدرجات',
            'delete_grades' => 'حذف الدرجات',
            'grades_reports' => 'تقارير الدرجات',

            // الرسوم الدراسية
            'view_fees' => 'عرض الرسوم',
            'create_fees' => 'إنشاء رسوم',
            'edit_fees' => 'تعديل الرسوم',
            'delete_fees' => 'حذف الرسوم',

            // المدفوعات
            'view_payments' => 'عرض المدفوعات',
            'create_payments' => 'تسجيل مدفوعات',
            'edit_payments' => 'تعديل المدفوعات',
            'delete_payments' => 'حذف المدفوعات',
            'financial_reports' => 'التقارير المالية',

            // المكتبة
            'view_library' => 'عرض المكتبة',
            'create_library' => 'إضافة كتب',
            'edit_library' => 'تعديل الكتب',
            'delete_library' => 'حذف الكتب',
            'issue_books' => 'إعارة الكتب',

            // الإشعارات
            'view_notifications' => 'عرض الإشعارات',
            'create_notifications' => 'إنشاء إشعارات',
            'delete_notifications' => 'حذف الإشعارات',

            // الرسائل
            'view_messages' => 'عرض الرسائل',
            'send_messages' => 'إرسال رسائل',
            'delete_messages' => 'حذف الرسائل',

            // التقارير
            'view_reports' => 'عرض التقارير',
            'export_reports' => 'تصدير التقارير',

            // الإعدادات
            'view_settings' => 'عرض الإعدادات',
            'edit_settings' => 'تعديل الإعدادات',
            'system_backup' => 'النسخ الاحتياطي',
        ];

        $count = 0;
        foreach ($permissions as $permission => $displayName) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create([
                    'name' => $permission,
                    'display_name' => $displayName,
                    'guard_name' => 'web'
                ]);
                $count++;
            }
        }

        $this->command->info("✅ تم إنشاء {$count} صلاحية جديدة");
    }

    /**
     * إنشاء الأدوار
     */
    private function createRoles()
    {
        $this->command->info('👥 إنشاء الأدوار...');

        $roles = [
            'super_admin' => [
                'display_name' => 'مدير عام',
                'description' => 'صلاحيات كاملة لإدارة النظام',
                'permissions' => 'all' // جميع الصلاحيات
            ],

            'admin' => [
                'display_name' => 'مدير المدرسة',
                'description' => 'إدارة شؤون المدرسة',
                'permissions' => [
                    'view_dashboard',
                    'view_users', 'create_users', 'edit_users',
                    'view_students', 'create_students', 'edit_students', 'delete_students', 'export_students',
                    'view_teachers', 'create_teachers', 'edit_teachers', 'delete_teachers',
                    'view_classes', 'create_classes', 'edit_classes', 'delete_classes',
                    'view_subjects', 'create_subjects', 'edit_subjects', 'delete_subjects',
                    'view_timetable', 'create_timetable', 'edit_timetable',
                    'view_attendance', 'attendance_reports',
                    'view_exams', 'create_exams', 'edit_exams',
                    'view_grades', 'grades_reports',
                    'view_fees', 'create_fees', 'edit_fees',
                    'view_payments', 'financial_reports',
                    'view_notifications', 'create_notifications',
                    'view_messages', 'send_messages',
                    'view_reports', 'export_reports',
                    'view_settings'
                ]
            ],

            'teacher' => [
                'display_name' => 'معلم',
                'description' => 'تدريس المواد وإدارة الفصل',
                'permissions' => [
                    'view_dashboard',
                    'view_students',
                    'view_classes',
                    'view_subjects',
                    'view_timetable',
                    'view_attendance', 'create_attendance', 'edit_attendance',
                    'view_exams', 'create_exams', 'edit_exams',
                    'view_grades', 'create_grades', 'edit_grades',
                    'view_messages', 'send_messages'
                ]
            ],

            'student' => [
                'display_name' => 'طالب',
                'description' => 'الوصول للدرجات والحضور',
                'permissions' => [
                    'view_dashboard',
                    'view_timetable',
                    'view_attendance',
                    'view_grades',
                    'view_exams',
                    'view_library',
                    'view_messages'
                ]
            ],

            'parent' => [
                'display_name' => 'ولي أمر',
                'description' => 'متابعة أداء الطالب',
                'permissions' => [
                    'view_dashboard',
                    'view_students',
                    'view_attendance',
                    'view_grades',
                    'view_fees',
                    'view_payments',
                    'view_messages', 'send_messages'
                ]
            ],

            'accountant' => [
                'display_name' => 'محاسب',
                'description' => 'إدارة الشؤون المالية',
                'permissions' => [
                    'view_dashboard',
                    'view_students',
                    'view_fees', 'create_fees', 'edit_fees', 'delete_fees',
                    'view_payments', 'create_payments', 'edit_payments', 'delete_payments',
                    'financial_reports',
                    'view_reports', 'export_reports'
                ]
            ],

            'librarian' => [
                'display_name' => 'أمين المكتبة',
                'description' => 'إدارة المكتبة والكتب',
                'permissions' => [
                    'view_dashboard',
                    'view_students',
                    'view_teachers',
                    'view_library', 'create_library', 'edit_library', 'delete_library',
                    'issue_books'
                ]
            ],

            'secretary' => [
                'display_name' => 'سكرتير',
                'description' => 'أعمال السكرتارية والمراسلات',
                'permissions' => [
                    'view_dashboard',
                    'view_students', 'create_students', 'edit_students',
                    'view_teachers',
                    'view_messages', 'send_messages',
                    'view_notifications', 'create_notifications'
                ]
            ]
        ];

        $count = 0;
        foreach ($roles as $roleName => $roleData) {
            if (!Role::where('name', $roleName)->exists()) {
                $role = Role::create([
                    'name' => $roleName,
                    'display_name' => $roleData['display_name'],
                    'guard_name' => 'web'
                ]);

                // تعيين الصلاحيات
                if ($roleData['permissions'] === 'all') {
                    $role->givePermissionTo(Permission::all());
                    $this->command->info("✅ {$roleData['display_name']}: جميع الصلاحيات");
                } else {
                    $validPermissions = [];
                    foreach ($roleData['permissions'] as $permission) {
                        if (Permission::where('name', $permission)->exists()) {
                            $validPermissions[] = $permission;
                        }
                    }
                    if (!empty($validPermissions)) {
                        $role->givePermissionTo($validPermissions);
                    }
                    $this->command->info("✅ {$roleData['display_name']}: " . count($validPermissions) . " صلاحية");
                }

                $count++;
            } else {
                $this->command->warn("⚠️  الدور '{$roleData['display_name']}' موجود بالفعل");
            }
        }

        $this->command->info("✅ تم إنشاء {$count} أدوار جديدة");
    }
}
