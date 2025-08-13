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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->foreignIdFor('App\Models\Subject', 'subject_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->json('options'); // { "options" : [] , "correct_option" : [] }
            $table->enum('question_type', ['single_choice', 'multiple_choice']);
            $table->string('mark')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
