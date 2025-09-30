<?php

namespace Database\Factories;

use App\Models\TeacherProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeacherProfile>
 */
class TeacherProfileFactory extends Factory
{
    protected $model = TeacherProfile::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $subjects = [
            'اللغة العربية',
            'الرياضيات',
            'العلوم',
            'الدراسات الاجتماعية',
            'اللغة الإنجليزية',
            'التربية الإسلامية',
        ];

        return [
            'teacher_code' => $this->faker->unique()->numerify('TCH-###'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'specialization' => $this->faker->randomElement($subjects),
            'qualification' => 'بكالوريوس في ' . $this->faker->word(),
            'hire_date' => $this->faker->dateTimeBetween('-10 years', '-1 year'),
            'experience_years' => $this->faker->numberBetween(1, 20),
            'phone_secondary' => $this->faker->optional()->phoneNumber(),
            'address' => $this->faker->address(),
            'subjects' => $this->faker->randomElements($subjects, $this->faker->numberBetween(1, 3)),
            'office_hours' => 'الأحد - الخميس ' . $this->faker->numberBetween(8, 10) . 'ص - ' . $this->faker->numberBetween(1, 3) . 'م',
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
