<?php

namespace Tests\Feature;

use App\Modules\Role\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManegeRolesTest extends TestCase
{

    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = Role::whereName('systemAdmin')->first()->users()->first();
    }

    /** @test */
    public function an_authorized_user_can_view_all_roles_in_pagination()
    {
        $this->signIn($this->systemAdmin);

        create(Role::class, 5);

        $response = $this->get('roles?pageSize=20')
            ->assertStatus(200)
            ->json();

        $this->assertCount(6, $response['data']);

        create(Role::class, 20);

        $response = $this->get('roles?pageSize=20')
            ->assertStatus(200)
            ->json();

        $this->assertCount(20, $response['data']);
        $this->assertEquals(26, $response['total']);
    }

    /** @test */
    public function an_unauthorized_user_cannot_view_roles()
    {
        $this->signIn()->withExceptionHandling();

        create(Role::class, 5);

        $this->get('roles')
            ->assertStatus(403);
    }
}
