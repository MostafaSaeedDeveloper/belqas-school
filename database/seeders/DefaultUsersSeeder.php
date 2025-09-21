<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👤 إنشاء المستخدمين الافتراضيين...');

        $users = [
            [
                'name' => 'مدير النظام',
                'username' => 'admin',
                'email' => 'admin@belqas-school.com',
                'password' => Hash::make('123456789'),
                'role' => 'super_admin'
            ],
            [
                'name' => 'مدير المدرسة',
                'username' => 'principal',
                'email' => 'principal@belqas-school.com',
                'password' => Hash::make('123456789'),
                'role' => 'admin'
            ],
            [
                'name' => 'أحمد محمد - معلم',
                'username' => 'teacher',
                'email' => 'teacher@belqas-school.com',
                'password' => Hash::make('123456789'),
                'role' => 'teacher'
            ],
            [
                'name' => 'علي أحمد - طالب',
                'username' => 'student',
                'email' => 'student@belqas-school.com',
                'password' => Hash::make('123456789'),
                'role' => 'student'
            ],
            [
                'name' => 'محمود علي - ولي أمر',
                'username' => 'parent',
                'email' => 'parent@belqas-school.com',
                'password' => Hash::make('123456789'),
                'role' => 'parent'
            ],
            [
                'name' => 'سارة أحمد - محاسبة',
                'username' => 'accountant',
                'email' => 'accountant@belqas-school.com',
                'password' => Hash::make('123456789'),
                'role' => 'accountant'
            ]
        ];

        $count = 0;
        foreach ($users as $userData) {
            $roleName = $userData['role'];
            unset($userData['role']);

            // التحقق من عدم وجود المستخدم
            if (!User::where('username', $userData['username'])->exists()) {
                $userData['email_verified_at'] = now();
                $user = User::create($userData);

                // تعيين الدور
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $user->assignRole($role);
                    $this->command->info("✅ {$user->name} - Username: {$user->username} - {$role->display_name}");
                    $count++;
                } else {
                    $this->command->error("❌ الدور '{$roleName}' غير موجود");
                }
            } else {
                $this->command->warn("⚠️  المستخدم '{$userData['username']}' موجود بالفعل");
            }
        }

        $this->command->info("✅ تم إنشاء {$count} مستخدمين جدد");

        if ($count > 0) {
            $this->command->info('');
            $this->command->info('🔐 بيانات تسجيل الدخول:');
            $this->command->info('   Username: admin     - Password: 123456789');
            $this->command->info('   Username: principal - Password: 123456789');
            $this->command->info('   Username: teacher   - Password: 123456789');
            $this->command->info('   Username: student   - Password: 123456789');
            $this->command->info('   Username: parent    - Password: 123456789');
            $this->command->info('   Username: accountant- Password: 123456789');
            $this->command->warn('⚠️  يرجى تغيير كلمات المرور بعد أول تسجيل دخول!');
        }
    }
}
