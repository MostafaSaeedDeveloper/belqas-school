<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Teacher extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUSES = [
        'active' => 'على رأس العمل',
        'vacation' => 'إجازة',
        'suspended' => 'موقوف',
        'resigned' => 'مستقيل',
    ];

    protected $fillable = [
        'employee_code',
        'name',
        'english_name',
        'email',
        'phone',
        'specialization',
        'hire_date',
        'salary',
        'status',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function (Builder $builder, string $search) {
            $builder->where(function (Builder $query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('english_name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        });

        $query->when($filters['status'] ?? null, function (Builder $builder, string $status) {
            $builder->where('status', $status);
        });
    }

    public static function generateEmployeeCode(): string
    {
        do {
            $code = sprintf('EMP-%s', Str::upper(Str::random(6)));
        } while (static::where('employee_code', $code)->exists());

        return $code;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? 'غير محدد';
    }
}
