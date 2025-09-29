<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('assessment_date');
            $table->unsignedInteger('max_score');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['subject_id', 'classroom_id', 'name', 'assessment_date'], 'assessment_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
