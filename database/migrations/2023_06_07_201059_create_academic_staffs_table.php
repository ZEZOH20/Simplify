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
        Schema::create('academic_staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name',20);
            $table->string('verbose_title');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('img')->nullable();
            $table->string('department');
            $table->string('degree');
            $table->enum('title',['professor','instructor']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_staffs');
    }
};
