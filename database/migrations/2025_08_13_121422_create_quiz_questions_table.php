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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Quiz::class, 'quiz_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Question::class, 'question_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('order')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
