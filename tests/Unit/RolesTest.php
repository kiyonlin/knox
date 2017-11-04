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
        $perms = Permission::all()->take(3)->pluck('id')->toArray();
        $role->attachPermissions($perms);

        foreach($role->modulePermissionTree() as $module) {
            if( $module->is_leaf ){
                foreach($module->perms as $perm){
                    $this->assertEquals(in_array($perm->id, $perms), $perm->selected);
                }
            } else {
                foreach ($module->subModules as $subModule) {
                    foreach ($subModule->perms as $perm) {
                        $this->assertEquals(in_array($perm->id, $perms), $perm->selected);
                    }
                }
            }
        }
    }
}
