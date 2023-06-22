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
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('course_code')->unsigned();
            $table->double('Web Development')->default(0);
            $table->double('Mobile Development')->default(0);
            $table->double('Cloud Engineering')->default(0);
            $table->double('Design')->default(0);
            $table->double('Network')->default(0);
            $table->double('Security')->default(0);
            $table->double('Embeded Systems')->default(0);
            $table->double('Artificial intelligence')->default(0);
            $table->double('Software Testing')->default(0);
            $table->double('Programming')->default(0);
            $table->double('Data Science')->default(0);
            $table->double('Game Programming')->default(0);
            $table->double('Database')->default(0);
            $table->double('Business Intelligence')->default(0);
            $table->double('score')->default(0);
            $table->timestamps();
            // $fields = Field::all();
            // foreach ($fields as $field) {
            //     $table->double($field->name)->default(0);
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
