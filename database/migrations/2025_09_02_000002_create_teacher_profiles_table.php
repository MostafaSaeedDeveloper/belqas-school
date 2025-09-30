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
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('teacher_code')->nullable()->unique();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('specialization')->nullable();
            $table->string('qualification')->nullable();
            $table->date('hire_date')->nullable();
            $table->unsignedTinyInteger('experience_years')->nullable();
            $table->string('phone_secondary')->nullable();
            $table->string('address')->nullable();
            $table->json('subjects')->nullable();
            $table->string('office_hours')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};
