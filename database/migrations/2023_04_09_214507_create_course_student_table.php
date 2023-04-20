<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->double('gpa')->nullable();
            $table->integer('score')->nullable();
            $table->integer('grade_point')->nullable();
            $table->date('year')->nullable();
            $table->foreignId('student_id')->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->bigInteger('course_code')->unsigned();
            $table->foreign('course_code')->references('course_code')->on('courses')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_student');
    }
};
