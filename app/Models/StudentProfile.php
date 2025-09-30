<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class StudentProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'student_code',
        'gender',
        'date_of_birth',
        'grade_level',
        'classroom',
        'enrollment_date',
        'guardian_name',
        'guardian_phone',
        'address',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
    ];

    /**
     * Get the owning user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor for formatted date of birth.
     */
    public function getFormattedBirthDateAttribute(): ?string
    {
        return $this->date_of_birth
            ? Carbon::parse($this->date_of_birth)->translatedFormat('d F Y')
            : null;
    }

    /**
     * Accessor for formatted enrollment date.
     */
    public function getFormattedEnrollmentDateAttribute(): ?string
    {
        return $this->enrollment_date
            ? Carbon::parse($this->enrollment_date)->translatedFormat('d F Y')
            : null;
    }
}
