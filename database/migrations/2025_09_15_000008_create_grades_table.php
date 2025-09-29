<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('score');
            $table->date('graded_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->unique(['enrollment_id', 'assessment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
