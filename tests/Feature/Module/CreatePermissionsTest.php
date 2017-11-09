<?php

namespace Tests\Feature;

use App\Modules\Module\Module;
use App\Modules\Permission\Permission;
use App\Modules\Role\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePermissionsTest extends TestCase
{

    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = Role::whereName(Role::SYSTEM_ADMIN)->first()->users()->first();
    }


    /** @test */
    public function an_authorized_user_can_add_permissions_in_a_module()
    {
        $this->signIn($this->systemAdmin);

        $module = create(Module::class);

        $this->post("/modules/{$module->id}/permissions", [
            'name'         => 'new_permission',
            'display_name' => 'new_permission_display_name',
            'description'  => 'descriptions'
        ])
            ->assertStatus(201)
            ->json();

        $this->assertCount(2, $module->perms);
    }

    /** @test */
    public function an_unauthorized_user_can_not_add_permissions_in_a_module()
    {
        $this->signIn()->withExceptionHandling();

        $module = create(Module::class);

        $this->post("/modules/{$module->id}/permissions", [])
            ->assertStatus(403);
    }

    /** @test */
    public function nobody_can_add_permissions_for_the_modules_belong_to_the_system_module()
    {
        $this->signIn($this->systemAdmin)->withExceptionHandling();

        $module = Module::where('key', Module::SYSTEM_MODULE)->first()->subModules()->first();

        $this->post("/modules/{$module->id}/permissions", [])
            ->assertStatus(403);
    }

    /** @test */
    public function a_permissions_requires_a_valid_name()
    {
        $this->addPermission(['name' => null])
            ->assertSessionHasErrors('name');

        $this->addPermission(['name' => str_random(256)])
            ->assertSessionHasErrors('name');

        $this->addPermission(['name' => Permission::first()->name])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_permissions_may_have_a_valid_display_name()
    {
        $this->addPermission(['display_name' => null])
            ->assertSessionMissing('display_name');

        $this->addPermission(['display_name' => str_random(256)])
            ->assertSessionHasErrors('display_name');
    }

    /** @test */
    public function a_permissions_may_have_a_valid_description()
    {
        $this->addPermission(['description' => null])
            ->assertSessionMissing('description');

        $this->addPermission(['description' => str_random(512)])
            ->assertSessionHasErrors('description');
    }

    /**
     * 测试新增权限时每个校验字段的通用函数
     *
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function addPermission($overrides = [])
    {
        $this->signIn($this->systemAdmin)
            ->withExceptionHandling();

        $module = create(Module::class);

        return $this->post("/modules/{$module->id}/permissions",
            raw(Permission::class, $overrides));
    }

    /** @test */
    public function an_authorized_user_can_update_permissions()
    {
        $this->updatePermission(['display_name' => 'new_display_name']);
        $this->updatePermission(['description' => 'new_description']);
    }

    private function updatePermission($patch)
    {
        $this->signIn($this->systemAdmin);

        $module = create(Module::class);
        $permission = create(Permission::class, ['module_id' => $module->id]);

        $this->patch("modules/{$module->id}/permissions/{$permission->id}", $patch)
            ->assertStatus(204);

        $this->assertDatabaseHas('permissions',
            array_merge($patch, ['id' => $permission->id])
        );
    }

    /** @test */
    public function a_permission_should_have_a_valid_display_name()
    {
        $this->updateInvalidPermission(['display_name' => str_random(256)])
            ->assertSessionHasErrors('display_name');
    }

    /** @test */
    public function a_permission_should_have_a_valid_description()
    {
        $this->updateInvalidPermission(['description' => str_random(512)])
            ->assertSessionHasErrors('description');
    }

    /**
     * 测试更新权限时每个校验字段的通用函数
     *
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function updateInvalidPermission($patch = [])
    {
        $this->signIn($this->systemAdmin)->withExceptionHandling();

        $module = create(Module::class);
        $permission = create(Permission::class, ['module_id' => $module->id]);

        return $this->patch("modules/{$module->id}/permissions/{$permission->id}", $patch);
    }

    /** @test */
    public function an_unauthorized_user_can_not_update_permissions_in_a_module()
    {
        $this->signIn()->withExceptionHandling();

        $module = create(Module::class);
        $permission = create(Permission::class, ['module_id' => $module->id]);

        $this->patch("modules/{$module->id}/permissions/{$permission->id}", [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authorized_user_can_delete_a_permission()
    {
        $this->signIn($this->systemAdmin);

        $module = create(Module::class);
        $permission = create(Permission::class, ['module_id' => $module->id]);

        $this->delete("modules/{$module->id}/permissions/{$permission->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('permissions', $permission->toArray());
    }

    /** @test */
    public function an_unauthorized_user_can_not_delete_a_permission()
    {
        $this->signIn()->withExceptionHandling();

        $module = create(Module::class);
        $permission = create(Permission::class, ['module_id' => $module->id]);

        $this->delete("modules/{$module->id}/permissions/{$permission->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function nobody_can_delete_permissions_of_the_modules_belong_to_the_system_module()
    {
        $this->signIn($this->systemAdmin)->withExceptionHandling();

        $module = Module::where('key', Module::SYSTEM_MODULE)->first()->subModules()->first();

        $this->delete("modules/{$module->id}/permissions/{$module->perms()->first()->id}")
            ->assertStatus(403);
    }
}
