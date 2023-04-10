<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Field;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        Course::all()->each(function($course){
            $fields = Field::all();
            $course->field()->saveMany($fields);
        });
    }
}
