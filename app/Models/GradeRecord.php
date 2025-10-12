<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'classroom_id',
        'recorded_by',
        'exam_schedule_id',
        'assessment_type',
        'score',
        'max_score',
        'grade',
        'notes',
    ];

    protected $casts = [
        'score' => 'float',
        'max_score' => 'float',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(ExamSchedule::class, 'exam_schedule_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getScorePercentageAttribute(): ?float
    {
        if ($this->max_score <= 0) {
            return null;
        }

        return round(($this->score / $this->max_score) * 100, 2);
    }
}
