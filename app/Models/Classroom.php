<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'grade_level',
        'section',
        'capacity',
        'homeroom_teacher_id',
        'description',
    ];

    /**
     * Get the homeroom teacher assigned to the classroom.
     */
    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    /**
     * The students assigned to the classroom.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_student', 'classroom_id', 'student_id')
            ->withTimestamps();
    }

    /**
     * The teachers associated with the classroom.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_teacher', 'classroom_id', 'teacher_id')
            ->withTimestamps();
    }

    /**
     * The subjects taught in the classroom.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'classroom_subject')
            ->withPivot('teacher_id')
            ->withTimestamps();
    }

    /**
     * Attendance sessions recorded for the classroom.
     */
    public function attendanceSessions(): HasMany
    {
        return $this->hasMany(AttendanceSession::class);
    }
}
