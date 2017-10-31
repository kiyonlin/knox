<?php

namespace Tests\Feature;

use App\Models\RBAC\Role;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUsersTest extends TestCase
{

    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = Role::whereName('systemAdmin')->first()->users()->first();
    }

    /** @test */
    public function an_authorized_user_can_add_a_new_user()
    {
        $this->signIn($this->systemAdmin);

        $newUser = raw(User::class);

        $this->postJson('users', $newUser);

        $this->assertDatabaseHas('users', $newUser);
    }

    /** @test */
    public function an_unauthorized_user_can_not_add_a_new_user()
    {
        // 登录一个没有新增用户权限的用户
        $this->signIn()->withExceptionHandling();

        $newUser = raw(User::class);

        $this->post('users', $newUser)
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_requires_a_valid_username()
    {
        $this->addUser(['username' => null])
            ->assertSessionHasErrors('username');

        $this->addUser(['username' => str_random(256)])
            ->assertSessionHasErrors('username');
    }

    /** @test */
    public function a_user_requires_a_valid_email()
    {
        $this->addUser(['email' => null])
            ->assertSessionHasErrors('email');

        $this->addUser(['email' => 'not_an_valid_email_address'])
            ->assertSessionHasErrors('email');

        $this->addUser(['email' => str_random(255) . '@163.com'])
            ->assertSessionHasErrors('email');

        $this->addUser(['email' => $this->systemAdmin->email])
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_user_requires_a_valid_password()
    {
        $this->addUser(['password' => null])
            ->assertSessionHasErrors('password');

        $this->addUser(['password' => '11111'])
            ->assertSessionHasErrors('password');
    }

    /**
     * 测试新增用户时每个校验字段的通用函数
     *
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function addUser($overrides = [])
    {
        $this->signIn($this->systemAdmin)
            ->withExceptionHandling();

        return $this->post('users', raw(User::class, $overrides));
    }

    /** @test */
    public function an_authorized_user_can_delete_a_user()
    {
        $this->signIn($this->systemAdmin);

        $user = create(User::class);

        $this->delete("users/{$user->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('users', $user->toArray());
    }

    /** @test */
    public function an_unauthorized_user_can_not_delete_a_user()
    {
        $this->signIn();

        $user = create(User::class);

        $this->deleteJson("users/{$user->id}")
            ->assertStatus(403);
    }
}
