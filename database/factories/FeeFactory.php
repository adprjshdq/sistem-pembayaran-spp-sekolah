<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Fee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fee>
 */
class FeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'academic_year_id' => AcademicYear::factory(),
            'level' => fake()->randomElement(['X', 'XI', 'XII']),
            'amount' => fake()->randomElement([300000, 350000, 400000]),
        ];
    }
}
