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
        // 1  unique

       // Create 10 records of customers
     User::factory(3)->create()->each(function ($user) {
        // Seed the relation with one address
        $student = Student::factory()->make();
        $user->student()->save($student);    
    });
    
    }
}
