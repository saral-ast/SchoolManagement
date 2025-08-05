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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Student::class,'student_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Classes::class,'class_id')->constrained()->cascadeOnDelete();
            $table->string('total_mark');
            $table->string('obtained_mark');
            $table->enum('exam_type',['mid_term','final','test']);
            $table->date('exam_date');
            $table->enum('grade',['A','B','C','D','E','F']);
            $table->enum('result_status',['pass','fail']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};