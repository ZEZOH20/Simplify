<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'name'=>$this->faker->word(),
        'course_code'=>$this->faker->randomNumber(7),
        'course_type'=>$this->faker->word(),
        'credit_hours'=>$this->faker->numberBetween(2,3),
        'brief_info'=>$this->faker->text(),
        ];
    }
}
