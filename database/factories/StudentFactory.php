<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'class_id' => SchoolClass::factory(),
            'nis' => fake()->unique()->numerify('2026####'),
            'name' => fake()->name(),
            'gender' => fake()->randomElement(['male', 'female']),
            'phone' => fake()->numerify('08##########'),
            'parent_name' => fake()->name(),
            'parent_phone' => fake()->numerify('08##########'),
            'address' => fake()->address(),
            'status' => 'active',
        ];
    }
}
