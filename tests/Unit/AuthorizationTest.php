<?php

namespace Tests\Unit;

use App\Modules\Module\Module;
use App\Modules\Permission\Permission;
use App\Modules\Role\Role;
use App\Modules\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorizationTest extends TestCase
{

    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = Role::whereName('systemAdmin')->first()->users()->first();
    }

    /** @test */
    public function users_can_fetch_their_authorized_modules()
    {
        // a user have a role
        $user = create(User::class);
        $user->attachRole($role = create(Role::class));

        // and the role have permission to some modules
        $modules = create(Module::class, 2)->each(function ($module) use ($role) {
            $role->attachPermission(create(Permission::class, [
                'name'         => "view_module_{$module->id}",
                'display_name' => "查看{$module->name}模块",
                'description'  => "查看{$module->name}模块",
            ]));
        });
        dd(buildModuleTree($this->systemAdmin->modules()->toArray()));
        // user can fetch these modules associated with the role
        $this->assertEquals($modules->values()->toArray(), $user->modules()->values()->toArray());

        // but cannot fetch those non-associated
        $this->assertNotEquals(create(Module::class, 2)->values()->toArray(), $user->modules()->values()->toArray());
    }

    /** @test */
    public function users_can_fetch_their_authorized_permissions()
    {
        // a user have two roles
        $user = create(User::class);
        $user->attachRole($role1 = create(Role::class));
        $user->attachRole($role2 = create(Role::class));

        // and the role1 have permissions
        $role1->attachPermissions(create(Permission::class, 5));

        // and the role2 have permissions
        $role2->attachPermissions(create(Permission::class, 3));

        // user can fetch these permissions associated with the role
        $allPermissions = $role1->perms->merge($role2->perms)->pluck('name');

        $this->assertEquals($allPermissions, $user->permissions());

        // but cannot fetch those non-associated
        $this->assertNotEquals(create(Permission::class, 2)->pluck('name'), $user->permissions());
    }
}
