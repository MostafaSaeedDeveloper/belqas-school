<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 بدء عملية إنشاء البيانات الأساسية...');

        $this->call([
            RolesAndPermissionsSeeder::class,
            DefaultUsersSeeder::class,
        ]);

        $this->command->info('🎉 تم الانتهاء من إنشاء جميع البيانات بنجاح!');
        $this->command->info('');
        $this->command->info('📧 بيانات تسجيل الدخول:');
        $this->command->info('   المدير العام: admin@belqas-school.com');
        $this->command->info('   مدير المدرسة: principal@belqas-school.com');
        $this->command->info('   المعلم: teacher@belqas-school.com');
        $this->command->info('   الطالب: student@belqas-school.com');
        $this->command->info('   ولي الأمر: parent@belqas-school.com');
        $this->command->info('   المحاسبة: accountant@belqas-school.com');
        $this->command->info('');
        $this->command->info('🔑 كلمة المرور للجميع: 123456789');
    }
}
