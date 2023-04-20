<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses =Course::factory(3)->create();

        // recursive relationShip course has many prerequest courses
        $courses->each(function($course){
            $course->prereq()->saveMany(
               Course::factory(2)->create()
            );
       });
        //many to many courses  Students 
        Student::all()->each(function ($student) use ($courses) {
                $student->course()->saveMany($courses);
        });

        
    }
}
  