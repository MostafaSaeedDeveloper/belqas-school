<?php

namespace Tests\Feature\Classrooms;

use App\Models\Classroom;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ClassroomManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_classes',
            'create_classes',
            'edit_classes',
            'delete_classes',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        $this->admin = User::factory()->create();
        $this->admin->givePermissionTo($permissions);
    }

    public function test_can_store_classroom_with_relationships(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $student = User::factory()->create();
        $student->assignRole('student');
        StudentProfile::factory()->for($student)->create();

        $subject = Subject::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('classes.store'), [
            'name' => 'فصل تمهيدي 1',
            'grade_level' => 'رياض الأطفال (KG1)',
            'capacity' => 25,
            'homeroom_teacher_id' => $teacher->id,
            'student_ids' => [$student->id],
            'subject_ids' => [$subject->id],
        ]);

        $response->assertRedirect();

        $classroom = Classroom::where('name', 'فصل تمهيدي 1')->first();

        $this->assertNotNull($classroom);
        $this->assertEquals('رياض الأطفال (KG1)', $classroom->grade_level);
        $this->assertTrue($classroom->teachers->contains('id', $teacher->id));
        $this->assertTrue($classroom->students->contains('id', $student->id));
        $this->assertTrue($classroom->subjects->contains('id', $subject->id));

        $student->refresh();
        $this->assertEquals($classroom->name, $student->studentProfile?->classroom);
        $this->assertEquals($classroom->grade_level, $student->studentProfile?->grade_level);
    }

    public function test_updating_classroom_refreshes_relationships(): void
    {
        $originalTeacher = User::factory()->create();
        $originalTeacher->assignRole('teacher');

        $newTeacher = User::factory()->create();
        $newTeacher->assignRole('teacher');

        $student = User::factory()->create();
        $student->assignRole('student');
        StudentProfile::factory()->for($student)->create([
            'classroom' => 'فصل قديم',
            'grade_level' => 'الصف الأول الابتدائي',
        ]);

        $subject = Subject::factory()->create();

        $classroom = Classroom::factory()->create([
            'name' => 'فصل 1',
            'grade_level' => 'الصف الأول الابتدائي',
            'homeroom_teacher_id' => $originalTeacher->id,
        ]);

        $classroom->teachers()->attach($originalTeacher->id);
        $classroom->students()->attach($student->id);
        $classroom->subjects()->attach($subject->id);

        $response = $this->actingAs($this->admin)->put(route('classes.update', $classroom), [
            'name' => 'فصل 1 محدث',
            'grade_level' => 'الصف الثاني الابتدائي',
            'capacity' => 30,
            'homeroom_teacher_id' => $newTeacher->id,
            'teacher_ids' => [$newTeacher->id],
            'student_ids' => [],
            'subject_ids' => [$subject->id],
        ]);

        $response->assertRedirect();

        $classroom->refresh();
        $student->refresh();

        $this->assertEquals('فصل 1 محدث', $classroom->name);
        $this->assertEquals('الصف الثاني الابتدائي', $classroom->grade_level);
        $this->assertTrue($classroom->teachers->contains('id', $newTeacher->id));
        $this->assertFalse($classroom->teachers->contains('id', $originalTeacher->id));
        $this->assertCount(0, $classroom->students);
        $this->assertNull($student->studentProfile?->classroom);
    }
}
