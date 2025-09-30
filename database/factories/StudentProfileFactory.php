<?php

namespace Database\Factories;

use App\Models\StudentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentProfile>
 */
class StudentProfileFactory extends Factory
{
    protected $model = StudentProfile::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $gradeLevels = [
            'رياض الأطفال (KG1)',
            'رياض الأطفال (KG2)',
            'الصف الأول الابتدائي',
            'الصف الثاني الابتدائي',
            'الصف الثالث الابتدائي',
            'الصف الرابع الابتدائي',
            'الصف الخامس الابتدائي',
            'الصف السادس الابتدائي',
            'الصف الأول الإعدادي',
            'الصف الثاني الإعدادي',
            'الصف الثالث الإعدادي',
            'الصف الأول الثانوي',
            'الصف الثاني الثانوي',
            'الصف الثالث الثانوي',
        ];

        return [
            'student_code' => $this->faker->unique()->numerify('STU-###'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->dateTimeBetween('-18 years', '-6 years'),
            'grade_level' => $this->faker->optional()->randomElement($gradeLevels),
            'classroom' => null,
            'enrollment_date' => $this->faker->dateTimeBetween('-3 years', 'now'),
            'guardian_name' => $this->faker->name(),
            'guardian_phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
