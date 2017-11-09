<?php

namespace Tests\Feature;

use App\Modules\Permission\Permission;
use App\Modules\Role\Role;
use App\Modules\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManegeRolesTest extends TestCase
{

    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = User::systemAdmin();
    }

    /** @test */
    public function an_authorized_user_can_view_all_roles_in_pagination()
    {
        $this->signIn($this->systemAdmin);

        create(Role::class, 5);

        $response = $this->get('roles?pageSize=20')
            ->assertStatus(200)
            ->json();

        $this->assertCount(6, $response['data']);

        create(Role::class, 20);

        $response = $this->get('roles?pageSize=20')
            ->assertStatus(200)
            ->json();

        $this->assertCount(20, $response['data']);
        $this->assertEquals(26, $response['total']);
    }

    /** @test */
    public function an_unauthorized_user_cannot_view_roles()
    {
        $this->signIn()->withExceptionHandling();

        create(Role::class, 5);

        $this->get('roles')
            ->assertStatus(403);
    }

    /** @test */
    public function an_authorized_user_can_get_module_permission_tree_of_a_role()
    {
        $this->signIn($this->systemAdmin);

        $role = create(Role::class);
        $role->attachPermissions(Permission::take(3)->pluck('id')->toArray());

        $response = $this->get("roles/{$role->id}/permissions")
            ->assertStatus(200)
            ->json();

        $this->assertCount(3, $response['defaultCheckedKeys']);
    }

    /** @test */
    public function an_unauthorized_user_can_not_get_module_permission_tree_of_a_role()
    {
        $this->signIn()
            ->withExceptionHandling()
            ->get('roles/1/permissions')
            ->assertStatus(403);
    }

    /** @test */
    public function an_authorized_user_can_update_module_permission_tree_of_a_role()
    {
        $this->signIn($this->systemAdmin);

        $role = create(Role::class);

        $this->assertEquals([], $role->perms->pluck('id')->toArray());

        $checkedPermissions = Permission::take(3)->pluck('id')->toArray();

        $this->put("roles/{$role->id}/permissions", [
                'checkedKeys' => $checkedPermissions
            ])
            ->assertStatus(204);

        $this->assertEquals($checkedPermissions, $role->fresh()->perms->pluck('id')->toArray());
    }

    /** @test */
    public function an_unauthorized_user_can_not_update_module_permission_tree_of_a_role()
    {
        $this->signIn()
            ->withExceptionHandling()
            ->put('roles/1/permissions')
            ->assertStatus(403);
    }

    /** @test */
    public function nobody_can_change_the_system_admins_permission()
    {
        $this->signIn($this->systemAdmin)->withExceptionHandling();

        $role = Role::whereName(Role::SYSTEM_ADMIN)->first();

        $this->put("roles/{$role->id}/permissions")
            ->assertStatus(403);
    }
}
