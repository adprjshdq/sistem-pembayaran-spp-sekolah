<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolClass>
 */
class SchoolClassFactory extends Factory
{
    public function definition(): array
    {
        $level = fake()->randomElement(['X', 'XI', 'XII']);
        $major = fake()->randomElement(['RPL', 'TKJ', 'MIPA']);

        return [
            'academic_year_id' => AcademicYear::factory(),
            'name' => $level.' '.$major.' '.fake()->unique()->numberBetween(1, 9),
            'level' => $level,
            'major' => $major,
            'homeroom_teacher' => fake()->name(),
        ];
    }
}
