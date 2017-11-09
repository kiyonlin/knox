<?php

namespace Tests\Feature;

use App\Modules\Role\Role;
use App\Modules\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateRolesTest extends TestCase
{
    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = User::systemAdmin();
    }

    /** @test */
    public function an_authorized_user_can_add_a_new_role()
    {
        $this->signIn($this->systemAdmin);

        $newRole = raw(Role::class);

        $this->postJson('roles', $newRole);

        $this->assertDatabaseHas('roles', $newRole);
    }

    /** @test */
    public function an_unauthorized_user_can_not_add_a_new_role()
    {
        // 登录一个没有新增角色权限的用户
        $this->signIn()->withExceptionHandling();

        $newRole = raw(Role::class);

        $this->post('roles', $newRole)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_requires_a_valid_name()
    {
        $this->addRole(['name' => null])
            ->assertSessionHasErrors('name');

        $this->addRole(['name' => str_random(256)])
            ->assertSessionHasErrors('name');

        $this->addRole(['name' => Role::SYSTEM_ADMIN])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_user_may_have_a_valid_display_name()
    {
        $this->addRole(['display_name' => null])
            ->assertSessionMissing('display_name');

        $this->addRole(['display_name' => str_random(256)])
            ->assertSessionHasErrors('display_name');
    }

    /** @test */
    public function a_user_may_have_a_valid_description()
    {
        $this->addRole(['description' => null])
            ->assertSessionMissing('description');

        $this->addRole(['description' => str_random(512)])
            ->assertSessionHasErrors('description');
    }

    /**
     * 测试新增用户时每个校验字段的通用函数
     *
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function addRole($overrides = [])
    {
        $this->signIn($this->systemAdmin)
            ->withExceptionHandling();

        return $this->post('roles', raw(Role::class, $overrides));
    }

    /** @test */
    public function an_authorized_user_can_update_roles()
    {
        $this->updateRole(['description' => 'new_description']);
        $this->updateRole(['display_name' => 'new_display_name']);
    }

    private function updateRole($patch)
    {
        $this->signIn($this->systemAdmin);

        $role = create(Role::class);

        $this->patch("roles/{$role->id}", $patch)
            ->assertStatus(204);

        $this->assertDatabaseHas('roles',
            array_merge($patch, ['id' => $role->id])
        );
    }

    /** @test */
    public function a_role_should_have_a_valid_display_name()
    {
        $this->updateInvalidRole(['display_name' => str_random(256)])
            ->assertSessionHasErrors('display_name');
    }

    /** @test */
    public function a_role_should_have_a_valid_description()
    {
        $this->addRole(['description' => str_random(512)])
            ->assertSessionHasErrors('description');
    }

    /**
     * 测试新增角色时每个校验字段的通用函数
     *
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function updateInvalidRole($overrides = [])
    {
        $this->signIn($this->systemAdmin)
            ->withExceptionHandling();

        return $this->post('roles', raw(Role::class, $overrides));
    }

    /** @test */
    public function an_unauthorized_user_cannot_update_roles()
    {
        $this->signIn()->withExceptionHandling();

        $this->patch("roles/{$this->systemAdmin->id}", ['display_name' => 'new_display_name'])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authorized_user_can_delete_a_role()
    {
        $this->signIn($this->systemAdmin);

        $role = create(Role::class);

        $this->delete("roles/{$role->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('roles', $role->toArray());
    }

    /** @test */
    public function an_unauthorized_user_can_not_delete_a_role()
    {
        $this->signIn()->withExceptionHandling();

        $role = create(Role::class);

        $this->deleteJson("roles/{$role->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function nobody_can_not_delete_system_admin_role()
    {
        $this->signIn($this->systemAdmin)->withExceptionHandling();

        $role = Role::whereName(Role::SYSTEM_ADMIN)->first();

        $this->delete("roles/{$role->id}")
            ->assertStatus(403);
    }
}
