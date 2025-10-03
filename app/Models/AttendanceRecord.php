<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    public const STATUS_PRESENT = 'present';
    public const STATUS_ABSENT = 'absent';
    public const STATUS_LATE = 'late';
    public const STATUS_EXCUSED = 'excused';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'attendance_session_id',
        'student_id',
        'status',
        'remarks',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    /**
     * The session this record belongs to.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(AttendanceSession::class, 'attendance_session_id');
    }

    /**
     * The student associated with the record.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the available attendance statuses.
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_PRESENT => 'حاضر',
            self::STATUS_ABSENT => 'غائب',
            self::STATUS_LATE => 'متأخر',
            self::STATUS_EXCUSED => 'مستأذن',
        ];
    }
}
