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
        Schema::create('subject_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Result::class,'result_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Subject::class,'subject_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Student::class,'student_id')->constrained()->cascadeOnDelete();
            $table->string('total_mark')->default('100');
            $table->string('obtained_mark');
            $table->enum('exam_type',['mid_term','final','test']);
            $table->enum('grade',['A','B','C','D','E','F']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_marks');
    }
};