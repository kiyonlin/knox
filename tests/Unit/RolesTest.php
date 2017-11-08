<?php

namespace Tests\Unit;

use App\Modules\Permission\Permission;
use App\Modules\Role\Role;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolesTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_may_have_some_perms()
    {
        $this->assertInstanceOf(Collection::class, create(Role::class)->perms);
    }

    /** @test */
    public function it_can_get_module_permission_tree()
    {
        $role = create(Role::class);
        $perms = Permission::all(['id', 'name'])->take(3);
        $role->attachPermissions($perms->pluck('id')->toArray());

        $result = $role->modulePermissionTree();

        $this->assertEquals($perms->pluck('id')->toArray(), $result['defaultCheckedKeys']);
    }

    /** @test */
    public function it_will_take_name_as_display_name_when_it_is_null()
    {
        $role = create(Role::class);
        $this->assertEquals($role->name, $role->display_name);

        $anotherRole = create(Role::class, ['display_name' => 'display_name']);
        $this->assertNotEquals($anotherRole->name, $anotherRole->display_name);
    }
}
