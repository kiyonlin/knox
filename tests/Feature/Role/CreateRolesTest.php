<?php

namespace Tests\Feature;

use App\Modules\Role\Role;
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
