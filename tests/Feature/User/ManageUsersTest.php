<?php

namespace Tests\Feature;

use App\Modules\Role\Role;
use App\Modules\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManegeUsersTest extends TestCase
{

    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = User::systemAdmin();
    }

    /** @test */
    public function an_authorized_user_can_view_all_users_in_pagination()
    {
        $this->signIn($this->systemAdmin);

        create(User::class, 5);

        $response = $this->get('users?pageSize=20')
            ->assertStatus(200)
            ->json();

        $this->assertCount(6, $response['data']);

        create(User::class, 20);

        $response = $this->get('users?pageSize=20')
            ->assertStatus(200)
            ->json();

        $this->assertCount(20, $response['data']);
        $this->assertEquals(26, $response['total']);
    }

    /** @test */
    public function an_unauthorized_user_cannot_view_users()
    {
        $this->signIn()->withExceptionHandling();

        create(User::class, 5);

        $this->get('users')
            ->assertStatus(403);
    }

    /** @test */
    public function an_authorized_user_can_get_all_roles_that_do_not_attach_to_a_given_user()
    {
        $user = create(User::class);
        $this->signIn($this->systemAdmin);
        create(Role::class, 5);
        $user->attachRole(Role::first());

        $response = $this->getJson("/users/{$user->id}/roles")
            ->assertStatus(200)
            ->json();

        $this->assertEquals(Role::all()->count() - 1, count($response));
    }

    /** @test */
    public function an_unauthorized_user_can_not_get_all_roles_that_do_not_attach_to_a_given_user()
    {
        $this->signIn()->withExceptionHandling();
        $user = create(User::class);

        $this->getJson("/users/{$user->id}/roles")
            ->assertStatus(403);

    }

    /** @test */
    public function an_authorized_user_can_attach_a_role_to_a_user()
    {
        $this->signIn($this->systemAdmin);

        $user = create(User::class);
        $role = create(Role::class);

        $this->putJson("/users/{$user->id}/roles/{$role->id}")
            ->assertStatus(204);

        $this->assertTrue($user->roles->contains($role));
    }

    /** @test */
    public function an_unauthorized_user_can_not_attach_a_role_to_a_user()
    {
        $this->signIn()->withExceptionHandling();

        $user = create(User::class);
        $role = create(Role::class);

        $this->putJson("/users/{$user->id}/roles/{$role->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function an_authorized_user_can_detach_a_role_from_a_user()
    {
        $this->signIn($this->systemAdmin);

        $user = create(User::class);
        $role = create(Role::class);
        $user->attachRole($role);

        $this->assertTrue($user->roles->contains($role));

        $this->deleteJson("/users/{$user->id}/roles/{$role->id}")
            ->assertStatus(204);

        $this->assertFalse($user->fresh()->roles->contains($role));
    }

    /** @test */
    public function nobody_can_detach_the_system_admin_role_from_the_system_admin_user()
    {
        $this->signIn($this->systemAdmin)->withExceptionHandling();
        $role = Role::whereName(Role::SYSTEM_ADMIN)->first();

        $this->deleteJson("/users/{$this->systemAdmin->id}/roles/{$role->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function an_unauthorized_user_can_not_detach_a_role_from_a_user()
    {
        $this->signIn()->withExceptionHandling();

        $user = create(User::class);
        $role = create(Role::class);

        $this->deleteJson("/users/{$user->id}/roles/{$role->id}")
            ->assertStatus(403);
    }
}
