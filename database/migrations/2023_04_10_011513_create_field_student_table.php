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
            $table->decimal('progress',3,2,true)->default(0.000);
            $table->unsignedInteger('active')->default(0);
            $table->unsignedInteger('panding')->nullable();
            $table->foreignId('student_id')->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->foreignId('field_id')->constrained()
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
