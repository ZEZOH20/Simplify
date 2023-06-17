<?php

use App\Models\Field;
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
        Schema::create('field_student', function (Blueprint $table) {
            $table->id();
            $table->double('progress')->default(0);
            $table->unsignedInteger('active')->default(0);
            $table->unsignedInteger('panding')->nullable();
            $table->enum('score',[1,2,3,4,5])->nullable(); // favorite percentage
            $table->foreignId('student_id')->constrained()
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
        Schema::dropIfExists('field_student');
    }
};
