<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1  unique one to one relationShip
     $student = Student::factory()->make();
       
     //one to one user student
     User::factory()->create()->each(function ($user) use ($student) {
        // Seed the relation with one address
        $user->student()->save($student);
    });
    
    }
}
