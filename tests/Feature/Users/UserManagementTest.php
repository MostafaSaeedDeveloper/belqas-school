<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Role $managerRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpPermissions();
        $this->createAdminUser();
    }

    /** @test */
    public function it_displays_the_users_list(): void
    {
        $users = collect(range(1, 3))->map(function (int $index) {
            $user = User::factory()->create([
                'username' => 'user_' . $index,
                'email' => "user{$index}@example.com",
            ]);

            $user->assignRole($this->managerRole);

            return $user;
        });

        $response = $this->actingAs($this->admin)->get(route('users.index'));

        $response->assertOk();
        $response->assertSee('إجمالي المستخدمين');
        $response->assertSee($users->first()->name);
    }

    /** @test */
    public function it_creates_a_new_user(): void
    {
        Storage::fake('public');

        $payload = [
            'name' => 'أحمد محمد',
            'username' => 'ahmed.mohamed',
            'email' => 'ahmed@example.com',
            'phone' => '01000123456',
            'password' => 'secretPass1',
            'password_confirmation' => 'secretPass1',
            'role' => $this->managerRole->name,
            'active' => 1,
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        $response = $this->actingAs($this->admin)->post(route('users.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'ahmed@example.com',
            'username' => 'ahmed.mohamed',
            'active' => true,
        ]);

        $user = User::where('email', 'ahmed@example.com')->first();
        $this->assertTrue(Hash::check('secretPass1', $user->password));
        $this->assertTrue($user->hasRole($this->managerRole));
        Storage::disk('public')->assertExists($user->avatar);
    }

    /** @test */
    public function it_updates_an_existing_user(): void
    {
        $user = User::factory()->create([
            'username' => 'old.username',
            'email' => 'old@example.com',
            'phone' => '0100100100',
        ]);
        $user->assignRole($this->managerRole);

        $payload = [
            'name' => 'الاسم الجديد',
            'username' => 'new.username',
            'email' => 'new@example.com',
            'phone' => '0100999888',
            'password' => '',
            'password_confirmation' => '',
            'role' => $this->managerRole->name,
            'active' => 0,
        ];

        $response = $this->actingAs($this->admin)->put(route('users.update', $user), $payload);

        $response->assertRedirect(route('users.show', $user));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'الاسم الجديد',
            'username' => 'new.username',
            'email' => 'new@example.com',
            'phone' => '0100999888',
            'active' => false,
        ]);
    }

    /** @test */
    public function it_prevents_deleting_the_authenticated_user(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('users.destroy', $this->admin));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    /** @test */
    public function it_deletes_a_user(): void
    {
        $user = User::factory()->create(['username' => 'delete.me']);
        $user->assignRole($this->managerRole);

        $response = $this->actingAs($this->admin)->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_toggles_user_status(): void
    {
        $user = User::factory()->create(['username' => 'toggle.user', 'active' => true]);
        $user->assignRole($this->managerRole);

        $response = $this->from(route('users.index'))
            ->actingAs($this->admin)
            ->patch(route('users.toggle-status', $user));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertFalse($user->fresh()->active);
    }

    /** @test */
    public function it_resets_user_password(): void
    {
        $user = User::factory()->create(['username' => 'reset.user']);
        $user->assignRole($this->managerRole);
        $originalPassword = $user->password;

        $response = $this->from(route('users.show', $user))
            ->actingAs($this->admin)
            ->post(route('users.reset-password', $user));

        $response->assertRedirect();
        $response->assertSessionHas('info');
        $this->assertNotEquals($originalPassword, $user->fresh()->password);
    }

    protected function setUpPermissions(): void
    {
        $permissions = collect(['view_users', 'create_users', 'edit_users', 'delete_users']);

        $permissions->each(fn (string $permission) => Permission::create([
            'name' => $permission,
            'guard_name' => 'web',
        ]));

        $this->managerRole = Role::create([
            'name' => 'manager',
            'guard_name' => 'web',
        ]);

        $adminRole = Role::create([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $adminRole->givePermissionTo($permissions->all());
        $this->managerRole->givePermissionTo($permissions->all());
    }

    protected function createAdminUser(): void
    {
        $this->admin = User::factory()->create([
            'username' => 'admin.user',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->admin->assignRole('super_admin');
    }
}
