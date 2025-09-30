<?php

namespace Database\Factories;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Classroom>
 */
class ClassroomFactory extends Factory
{
    protected $model = Classroom::class;

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
            'name' => 'فصل ' . $this->faker->unique()->numerify('###'),
            'grade_level' => $this->faker->randomElement($gradeLevels),
            'section' => $this->faker->optional()->randomLetter(),
            'capacity' => $this->faker->numberBetween(20, 35),
            'description' => $this->faker->sentence(),
        ];
    }
}
