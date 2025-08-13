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
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Quiz::class, 'quiz_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Student::class, 'student_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->integer('score')->default(0);
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
