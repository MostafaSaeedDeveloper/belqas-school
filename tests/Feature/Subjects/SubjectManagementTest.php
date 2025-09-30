<?php

namespace Tests\Feature\Subjects;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SubjectManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_subjects',
            'create_subjects',
            'edit_subjects',
            'delete_subjects',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);

        $this->admin = User::factory()->create();
        $this->admin->givePermissionTo($permissions);
    }

    public function test_can_store_subject_with_assignments(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $classroom = Classroom::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('subjects.store'), [
            'name' => 'العلوم المتقدمة',
            'code' => 'SCI-101',
            'grade_level' => 'الصف الثالث الإعدادي',
            'teacher_ids' => [$teacher->id],
            'classroom_ids' => [$classroom->id],
        ]);

        $response->assertRedirect();

        $subject = Subject::where('code', 'SCI-101')->first();
        $this->assertNotNull($subject);
        $this->assertTrue($subject->teachers->contains('id', $teacher->id));
        $this->assertTrue($subject->classrooms->contains('id', $classroom->id));
    }

    public function test_assigning_subject_to_classroom_updates_teacher_links(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $classroom = Classroom::factory()->create();
        $subject = Subject::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('subjects.assignments.store'), [
            'classroom_id' => $classroom->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
        ]);

        $response->assertRedirect();

        $classroom->refresh();
        $subject->refresh();
        $teacher->refresh();

        $this->assertEquals($teacher->id, $classroom->subjects()->first()->pivot->teacher_id);
        $this->assertTrue($classroom->teachers->contains('id', $teacher->id));
        $this->assertTrue($subject->teachers->contains('id', $teacher->id));
    }
}
