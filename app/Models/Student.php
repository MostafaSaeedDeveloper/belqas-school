<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUSES = [
        'active' => 'طالب منتظم',
        'inactive' => 'موقوف مؤقتًا',
        'graduated' => 'متخرج',
        'transferred' => 'منقول',
    ];

    public const GENDERS = [
        'male' => 'ذكر',
        'female' => 'أنثى',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_code',
        'name',
        'english_name',
        'gender',
        'birth_date',
        'national_id',
        'grade_level',
        'classroom',
        'enrollment_date',
        'status',
        'email',
        'phone',
        'guardian_name',
        'guardian_relationship',
        'guardian_phone',
        'address',
        'city',
        'transportation_method',
        'outstanding_fees',
        'medical_notes',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'birth_date' => 'date',
        'enrollment_date' => 'date',
        'outstanding_fees' => 'decimal:2',
    ];

    /**
     * Scope a query to apply the provided filters.
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function (Builder $builder, string $search) {
            $builder->where(function (Builder $query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('english_name', 'like', "%{$search}%")
                    ->orWhere('student_code', 'like', "%{$search}%")
                    ->orWhere('national_id', 'like', "%{$search}%")
                    ->orWhere('guardian_name', 'like', "%{$search}%");
            });
        });

        $query->when($filters['grade_level'] ?? null, function (Builder $builder, string $grade) {
            $builder->where('grade_level', $grade);
        });

        $query->when($filters['status'] ?? null, function (Builder $builder, string $status) {
            $builder->where('status', $status);
        });

        $query->when($filters['gender'] ?? null, function (Builder $builder, string $gender) {
            $builder->where('gender', $gender);
        });

        $query->when($filters['enrollment_year'] ?? null, function (Builder $builder, string $year) {
            $builder->whereYear('enrollment_date', $year);
        });
    }

    /**
     * Create a unique student code when none is provided.
     */
    public static function generateStudentCode(): string
    {
        do {
            $code = sprintf('STU-%s-%s', now()->format('Y'), Str::upper(Str::random(5)));
        } while (static::where('student_code', $code)->exists());

        return $code;
    }

    /**
     * Retrieve the localized label for the current status.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? 'غير محدد';
    }

    /**
     * Retrieve the localized label for the gender attribute.
     */
    public function getGenderLabelAttribute(): ?string
    {
        return $this->gender ? (self::GENDERS[$this->gender] ?? $this->gender) : null;
    }

    /**
     * Calculate the current age of the student.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->birth_date instanceof Carbon ? $this->birth_date->age : null;
    }

    /**
     * Extract the enrollment year from the enrollment date.
     */
    public function getEnrollmentYearAttribute(): ?string
    {
        return $this->enrollment_date instanceof Carbon ? $this->enrollment_date->format('Y') : null;
    }

    /**
     * Retrieve the initials of the student to use in avatars.
     */
    public function getInitialsAttribute(): string
    {
        $segments = preg_split('/\s+/u', trim($this->name));

        return Str::upper(Str::limit(collect($segments)->map(fn ($part) => Str::substr($part, 0, 1))->implode(''), 2, ''));
    }

    /**
     * Available statuses for selection controls.
     */
    public static function statusOptions(): array
    {
        return self::STATUSES;
    }

    /**
     * Available gender options for selection controls.
     */
    public static function genderOptions(): array
    {
        return self::GENDERS;
    }

    /**
     * Helper to normalise fillable payload.
     */
    public static function preparePayload(array $attributes): array
    {
        $allowed = (new static())->getFillable();

        return Arr::only($attributes, $allowed);
    }
}
