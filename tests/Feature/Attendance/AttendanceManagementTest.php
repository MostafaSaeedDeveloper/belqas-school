<?php

namespace Tests\Feature\Attendance;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Classroom;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AttendanceManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_attendance',
            'manage_attendance',
            'attendance_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        $this->manager = User::factory()->create();
        $this->manager->givePermissionTo($permissions);
    }

    public function test_user_can_store_daily_attendance(): void
    {
        $classroom = Classroom::factory()->create();

        $student = User::factory()->create();
        $student->assignRole('student');
        StudentProfile::factory()->for($student)->create();
        $classroom->students()->attach($student->id);

        $response = $this->actingAs($this->manager)->post(route('attendance.daily.store'), [
            'classroom_id' => $classroom->id,
            'date' => '2025-10-05',
            'notes' => 'تمت الحصة في معمل العلوم.',
            'records' => [
                $student->id => [
                    'status' => AttendanceRecord::STATUS_PRESENT,
                    'remarks' => 'حضر مبكراً',
                ],
            ],
        ]);

        $response->assertRedirect(route('attendance.daily', [
            'classroom_id' => $classroom->id,
            'date' => '2025-10-05',
        ]));

        $this->assertDatabaseHas('attendance_sessions', [
            'classroom_id' => $classroom->id,
            'date' => '2025-10-05',
            'notes' => 'تمت الحصة في معمل العلوم.',
            'status' => 'completed',
            'recorded_by' => $this->manager->id,
        ]);

        $this->assertDatabaseHas('attendance_records', [
            'student_id' => $student->id,
            'status' => AttendanceRecord::STATUS_PRESENT,
            'remarks' => 'حضر مبكراً',
        ]);
    }

    public function test_reports_view_displays_saved_records(): void
    {
        $classroom = Classroom::factory()->create();

        $student = User::factory()->create();
        $student->assignRole('student');
        StudentProfile::factory()->for($student)->create();

        $session = AttendanceSession::create([
            'classroom_id' => $classroom->id,
            'date' => '2025-10-05',
            'status' => 'completed',
            'recorded_by' => $this->manager->id,
            'notes' => 'حصة صباحية',
        ]);

        AttendanceRecord::create([
            'attendance_session_id' => $session->id,
            'student_id' => $student->id,
            'status' => AttendanceRecord::STATUS_ABSENT,
            'remarks' => 'إجازة مرضية',
        ]);

        $response = $this->actingAs($this->manager)->get(route('attendance.reports', [
            'classroom_id' => $classroom->id,
        ]));

        $response->assertOk();
        $response->assertSee('إجازة مرضية');
        $response->assertSee($student->name);
    }

    public function test_statistics_view_summarises_monthly_data(): void
    {
        $classroom = Classroom::factory()->create();

        $student = User::factory()->create();
        $student->assignRole('student');
        StudentProfile::factory()->for($student)->create();

        $session = AttendanceSession::create([
            'classroom_id' => $classroom->id,
            'date' => '2025-10-10',
            'status' => 'completed',
            'recorded_by' => $this->manager->id,
        ]);

        AttendanceRecord::create([
            'attendance_session_id' => $session->id,
            'student_id' => $student->id,
            'status' => AttendanceRecord::STATUS_PRESENT,
        ]);

        $response = $this->actingAs($this->manager)->get(route('attendance.statistics', [
            'month' => '2025-10',
        ]));

        $response->assertOk();
        $response->assertSee('حصص مسجلة');
        $response->assertSee('مرات الحضور');
        $response->assertSee('100%', false);
    }
}
