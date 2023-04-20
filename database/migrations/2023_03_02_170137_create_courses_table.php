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
    Schema::create('courses', function (Blueprint $table) {
        $table->bigInteger('course_code')->unsigned();
        $table->primary('course_code');
        
        $table->bigInteger('prereq_code')->unsigned()->nullable();
        $table->foreign('prereq_code')->references('course_code')->on('courses')
        ->onUpdate('cascade')
        ->onDelete('cascade');
    
        $table->string('name');
        $table->integer('credit_hours');
        $table->string('course_type');
        $table->string('brief_info');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
