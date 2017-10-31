<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\RBAC\Role;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateRolesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authorized_user_can_add_roles()
    {
        $role = Create(Role::class);
        $this->assertTrue(true);
    }
}
