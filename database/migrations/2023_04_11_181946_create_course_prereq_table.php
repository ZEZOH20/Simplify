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
        Schema::create('course_prereq', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->references('id')->on('courses');
            $table->foreignId('prereq_id')->nullable()->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade')->on('courses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_prereq');
    }
};
