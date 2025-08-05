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
        Schema::create('student_parents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\User::class,'user_id')->constrained()->cascadeOnDelete();
            $table->string('occupation');
            $table->string('relation');
            $table->string('secondary_phone')->nullable();
            $table->foreignIdFor(App\Models\Student::class,'student_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_parents');
    }
};