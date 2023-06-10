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
        Schema::create('staff_responsible_on_courses', function (Blueprint $table) {
            // $table->id();
            $table->bigInteger('course_code')->unsigned();
            $table->foreign('course_code')->references('course_code')->on('courses')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreignId('academic_staff_id')->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();

            $table->string('name')->default('test');

            $table->primary(['academic_staff_id','course_code']); //composite primary key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_responsible_on_courses');
    }
};
