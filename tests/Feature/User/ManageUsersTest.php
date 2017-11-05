<?php

namespace Tests\Feature;

use App\Modules\Role\Role;
use App\Modules\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManegeUsersTest extends TestCase
{

    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = Role::whereName('systemAdmin')->first()->users()->first();
    }

    /** @test */
    public function an_authorized_user_can_view_all_users_in_pagination()
    {
        $this->signIn($this->systemAdmin);

        create(User::class, 5);

        $response = $this->get('users?pageSize=20')
            ->assertStatus(200)
            ->json();

        $this->assertCount(6, $response['data']);

        create(User::class, 20);

        $response = $this->get('users?pageSize=20')
            ->assertStatus(200)
            ->json();

        $this->assertCount(20, $response['data']);
        $this->assertEquals(26, $response['total']);
    }

    /** @test */
    public function an_unauthorized_user_cannot_view_users()
    {
        $this->signIn()->withExceptionHandling();

        create(User::class, 5);

        $this->get('users')
            ->assertStatus(403);
    }

    /** @test */
    public function an_authorized_user_can_get_all_roles_that_do_not_attach_to_a_given_user()
    {
        $user = create(User::class);
        $this->signIn($this->systemAdmin);
        create(Role::class, 5);
        $user->attachRole(Role::first());

        $response = $this->getJson("/users/{$user->id}/roles")
            ->assertStatus(200)
            ->json();
dd($response);
        $this->assertEquals(Role::all()->count() - 1, count($response));
    }
}
