<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->enum('semester', [1, 2, 3]); // 1 = First, 2 = Second, 3 = Summer
            $table->string('academic_year');
            $table->enum('status', ['enrolled', 'dropped', 'completed'])->default('enrolled');
            $table->timestamps();

            // Prevent duplicate enrollments
            $table->unique(['student_id', 'subject_id', 'semester', 'academic_year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
};