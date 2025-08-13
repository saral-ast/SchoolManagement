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
        Schema::create('quiz_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\QuizAttempt::class, 'quiz_attempt_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Question::class, 'question_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->json('selected_options'); // { "selected_options" : [] }
            $table->boolean('is_correct')->default(false);
            $table->string('marks_awarded')->default('0');
            $table->string('time_taken')->default('0'); // Time taken to answer the question in seconds
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_responses');
    }
};
