<?php

namespace Tests\Unit;

use App\Modules\Permission\Permission;
use App\Modules\Role\Role;
use App\Modules\User\User;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{

    use RefreshDatabase;

    /**
     * TODO: 没有详细测试用户模块
     *
     * @test
     */
    public function it_has_some_authorized_modules()
    {
        $this->assertInstanceOf(Collection::class, create(User::class)->modules());
    }

    /** @test */
    public function it_has_some_authorized_permissions()
    {
        $user = create(User::class);
        $this->assertCount(0, $user->permissions());

        $role = create(Role::class);
        $user->attachRole($role);

        $this->assertCount(0, $user->fresh()->permissions());

        $role->attachPermissions(create(Permission::class, 5));
        $this->assertCount(5, $user->fresh()->permissions());

    }

    /** @test */
    public function it_knows_all_optional_roles()
    {
        $user = create(User::class);
        $this->assertCount(1, $user->optionalRoles());

        $user->attachRole(Role::first());
        $this->assertCount(0, $user->fresh()->optionalRoles());
    }
}
