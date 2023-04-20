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
        Schema::create('course_field', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('course_code')->unsigned();
            $table->foreign('course_code')->references('course_code')->on('courses')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->string('field_name',155);
            $table->foreign('field_name')->references('name')->on('fields')
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
        Schema::dropIfExists('field_course');
    }
};
