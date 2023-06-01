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
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedDouble('gpa_t1')->nullable();
            $table->unsignedDouble('gpa_t2')->nullable();
            $table->unsignedDouble('gpa_t3')->nullable();
            $table->unsignedDouble('gpa_t4')->nullable();
            $table->unsignedDouble('gpa_t5')->nullable();
            $table->unsignedDouble('gpa_t6')->nullable();
            $table->unsignedDouble('gpa_t7')->nullable();
            $table->unsignedDouble('gpa_t8')->nullable();

            $table->foreignId('student_id')->unique()->constrained('students')
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
        Schema::dropIfExists('terms');
    }
};
