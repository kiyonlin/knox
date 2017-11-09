<?php

namespace Tests\Feature;

use App\Modules\Role\Role;
use App\Modules\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUsersTest extends TestCase
{

    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = Role::whereName(Role::SYSTEM_ADMIN)->first()->users()->first();
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

    /** @test */
    public function nobody_can_not_delete_system_admin_role()
    {
        $this->signIn($this->systemAdmin);

        $this->deleteJson("users/{$this->systemAdmin->id}")
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
