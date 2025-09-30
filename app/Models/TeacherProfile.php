<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class TeacherProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'teacher_code',
        'gender',
        'specialization',
        'qualification',
        'hire_date',
        'experience_years',
        'phone_secondary',
        'address',
        'subjects',
        'office_hours',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'hire_date' => 'date',
        'subjects' => 'array',
    ];

    /**
     * Get the owning user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor for formatted hire date.
     */
    public function getFormattedHireDateAttribute(): ?string
    {
        return $this->hire_date
            ? Carbon::parse($this->hire_date)->translatedFormat('d F Y')
            : null;
    }

    /**
     * Accessor for subjects list as string.
     */
    public function getSubjectsListAttribute(): string
    {
        return collect($this->subjects ?? [])
            ->filter()
            ->map(fn ($subject) => trim($subject))
            ->implode('، ');
    }
}
