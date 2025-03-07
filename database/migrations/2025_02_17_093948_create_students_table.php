<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_id')->unique();
            $table->string('name'); 
            $table->string('email')->unique();
            $table->string('course');
            $table->integer('year_level');
            $table->timestamps();
            $table->boolean('is_archived')->default(false);
        });
    }

    public function down(): void
    {
        $table->dropColumn('is_archived');
    }
};
