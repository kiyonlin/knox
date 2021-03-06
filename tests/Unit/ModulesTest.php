<?php

namespace Tests\Unit;

use App\Modules\Module\Module;
use App\Modules\Permission\Permission;
use App\Modules\Role\Role;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModulesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_its_index()
    {
        $module = create(Module::class);
        $this->assertEquals($module->id, $module->index);

        $subModule = create(Module::class, ['pid' => $module->id]);
        $this->assertEquals("{$module->id}-{$subModule->id}", $subModule->index);

        $subSubModule = create(Module::class, ['pid' => $subModule->id]);
        $this->assertEquals("{$module->id}-{$subModule->id}-{$subSubModule->id}", $subSubModule->index);
    }

    /** @test */
    public function it_knows_its_level()
    {
        $module = create(Module::class);
        $this->assertEquals(1, $module->level);

        $subModule = create(Module::class, ['pid' => $module->id]);
        $this->assertEquals(2, $subModule->level);

        $subSubModule = create(Module::class, ['pid' => $subModule->id]);
        $this->assertEquals(3, $subSubModule->level);
    }

    /** @test */
    public function it_knows_if_it_is_leaf()
    {
        $module = create(Module::class);
        $this->assertTrue($module->is_leaf);

        $subModule = create(Module::class, ['pid' => $module->id]);
        $this->assertFalse($module->fresh()->is_leaf);
        $this->assertTrue($subModule->is_leaf);
    }

    /** @test */
    public function it_may_have_some_submodules()
    {
        $module = create(Module::class);
        $this->assertInstanceOf(Collection::class, $module->subModules);

        create(Module::class, ['pid' => $module->id], 3);

        $this->assertCount(3, $module->fresh()->subModules);
    }

    /** @test */
    public function it_may_have_some_permissions_when_it_is_a_leaf_one()
    {
        $module = create(Module::class);
        $this->assertCount(1, $module->perms);
    }

    /** @test */
    public function it_may_add_a_permission()
    {
        $module = create(Module::class);

        $perm = $module->addPerm(new Permission([
            'name' => 'new_permission'
        ]));

        $this->assertCount(2, $module->perms);
        $this->assertEquals('new_permission', $perm->name);
    }

    /** @test */
    public function it_will_auto_create_a_specific_view_permission_and_attache_to_the_system_admin_when_it_has_been_created()
    {
        $module = create(Module::class);
        $permissionName = "module.view.{$module->key}";

        $this->assertDatabaseHas('permissions', ['name' => $permissionName]);
        $this->assertTrue(Role::whereName(Role::SYSTEM_ADMIN)->first()->perms()->whereName($permissionName)->exists());
    }

    /** @test */
    public function associated_permissions_will_be_deleted_when_it_has_been_deleted()
    {
        $module = create(Module::class);
        $perm = create(Permission::class, ['module_id' => $module->id]);

        $module->delete();

        $this->assertDatabaseMissing('modules', ['id' => $module->id]);
        $this->assertDatabaseMissing('permissions', ['id' => $perm->id]);
    }
}
