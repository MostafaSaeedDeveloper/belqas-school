<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;

    public const STATUSES = [
        'enrolled' => 'مقيد',
        'graduated' => 'خريج',
        'withdrawn' => 'منسحب',
        'suspended' => 'موقوف',
    ];

    protected $fillable = [
        'user_id',
        'student_id',
        'admission_number',
        'first_name',
        'last_name',
        'full_name',
        'gender',
        'date_of_birth',
        'national_id',
        'passport_number',
        'nationality',
        'blood_type',
        'religion',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'guardian_name',
        'guardian_relation',
        'guardian_phone',
        'guardian_email',
        'guardian_address',
        'admission_date',
        'grade_id',
        'class_id',
        'section_id',
        'roll_number',
        'academic_year',
        'status',
        'profile_photo_path',
        'medical_info',
        'transportation',
        'previous_school',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function getDisplayNameAttribute(): string
    {
        if (!empty($this->full_name)) {
            return $this->full_name;
        }

        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? Str::title($this->status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'enrolled' => 'success',
            'graduated' => 'info',
            'withdrawn' => 'warning',
            'suspended' => 'danger',
            default => 'secondary',
        };
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (!$this->profile_photo_path) {
            return null;
        }

        if (Str::startsWith($this->profile_photo_path, ['http://', 'https://'])) {
            return $this->profile_photo_path;
        }

        return asset('storage/' . ltrim($this->profile_photo_path, '/'));
    }
}
