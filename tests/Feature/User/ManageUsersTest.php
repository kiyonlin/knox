<?php

namespace Tests\Feature;

use App\Models\RBAC\Role;
use App\Models\User;
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
    public function an_authorized_user_can_update_users()
    {
        $this->updateUser(['email' => 'example@163.com']);
        $this->updateUser(['display_name' => 'new_display_name']);
    }

    /** @test */
    public function an_unauthorized_user_cannot_update_users()
    {
        $this->signIn()->withExceptionHandling();

        $this->patch("users/{$this->systemAdmin->id}", ['email' => 'example@163.com'])
            ->assertStatus(403);
    }

    private function updateUser($patch)
    {
        $this->signIn($this->systemAdmin);

        $this->patch("users/{$this->systemAdmin->id}", $patch)
            ->assertStatus(204);

        $this->assertDatabaseHas('users',
            array_merge($patch, ['id' => $this->systemAdmin->id])
        );
    }
}
