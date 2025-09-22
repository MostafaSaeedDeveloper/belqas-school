<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Database\Seeder;

class AcademicStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏫 إنشاء الهيكل الأكاديمي الأساسي...');

        $grades = collect([
            ['name' => 'الصف الأول الابتدائي', 'code' => 'G1'],
            ['name' => 'الصف الثاني الابتدائي', 'code' => 'G2'],
            ['name' => 'الصف الثالث الابتدائي', 'code' => 'G3'],
        ])->mapWithKeys(function ($grade) {
            $gradeModel = Grade::firstOrCreate(
                ['code' => $grade['code']],
                ['name' => $grade['name']]
            );

            return [$grade['code'] => $gradeModel->id];
        });

        $classes = [
            ['grade_code' => 'G1', 'name' => '1A', 'code' => '1A'],
            ['grade_code' => 'G1', 'name' => '1B', 'code' => '1B'],
            ['grade_code' => 'G2', 'name' => '2A', 'code' => '2A'],
            ['grade_code' => 'G3', 'name' => '3A', 'code' => '3A'],
        ];

        $classMap = collect($classes)->mapWithKeys(function ($class) use ($grades) {
            $classModel = SchoolClass::firstOrCreate(
                ['code' => $class['code']],
                [
                    'name' => $class['name'],
                    'grade_id' => $grades[$class['grade_code']] ?? null,
                ]
            );

            return [$class['code'] => $classModel->id];
        });

        $sections = [
            ['class_code' => '1A', 'name' => 'الشعبة أ', 'code' => 'S1A'],
            ['class_code' => '1B', 'name' => 'الشعبة ب', 'code' => 'S1B'],
            ['class_code' => '2A', 'name' => 'الشعبة أ', 'code' => 'S2A'],
        ];

        foreach ($sections as $section) {
            Section::firstOrCreate(
                ['code' => $section['code']],
                [
                    'name' => $section['name'],
                    'class_id' => $classMap[$section['class_code']] ?? null,
                ]
            );
        }
    }
}
