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

        $this->assertEquals($perms->pluck('name')->toArray(), $result['defaultCheckedKeys']);
    }
}
