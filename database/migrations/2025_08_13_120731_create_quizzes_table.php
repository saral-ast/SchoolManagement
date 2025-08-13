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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->foreignIdFor(App\Models\Subject::class, 'subject_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Teacher::class, 'teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Classes::class, 'class_id')->constrained()->cascadeOnDelete();
            $table->string('total_questions')->default('10');
            $table->string('total_marks')->default('100');
            $table->enum('type',['random','mixed'])->default('mixed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
