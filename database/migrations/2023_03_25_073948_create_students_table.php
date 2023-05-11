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
        Schema::create('students', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedInteger('collage_id')->uniqid();
            $table->enum('gender',['male','female']);
            $table->string('img')->nullable();
            $table->unsignedInteger('t_credit')->nullable(); /* sumation of : elec_sim - man_sim - elec_univ - man_univ */
            $table->unsignedDouble('cgpa')->nullable();
            $table->unsignedInteger('elec_sim')->nullable();
            $table->unsignedInteger('man_sim')->nullable();
            $table->unsignedInteger('elec_univ')->nullable();
            $table->unsignedInteger('man_univ')->nullable();
            $table->unsignedInteger('current_term')->default(1);
            $table->foreignId('user_id')->unique()->constrained('users')
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
        Schema::dropIfExists('students');
    }
};
