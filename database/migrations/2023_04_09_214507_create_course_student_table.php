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
            $table->double('score')->nullable();
            $table->enum('term',[1,2,3,4,5,6,7,8]); //term numbers 8 because sim student must complete 4 years to graduate
            $table->enum('status',['active','finshed','failed'])->default('active');
            
            $table->foreignId('student_id')->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->bigInteger('course_code')->unsigned();
            $table->foreign('course_code')->references('course_code')->on('courses')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->primary(['student_id','course_code']); //composite primary key

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
