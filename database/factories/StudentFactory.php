<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $elec_sim =$this->faker->numberBetween(0,132);
        $man_sim =$this->faker->numberBetween(0,132);
        $elec_univ =$this->faker->numberBetween(0,132);
        $man_univ =$this->faker->numberBetween(0,132);

        return [
            't_credit'=>$elec_sim + $man_sim + $elec_univ + $man_univ,
            'cgpa'=>$this->faker->randomFloat(null,0,4),
            'elec_sim'=>$elec_sim, //you must check if the student enterd number same the elec_sim main list  
            'man_sim'=>$man_sim,
            'elec_univ'=>$elec_univ,
            'man_univ'=>$man_univ,
            'level'=>$this->faker->numberBetween(0,4), 
        ];
    }
}
