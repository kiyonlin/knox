<?php

namespace Tests\Feature;

use App\Modules\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{

    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = User::systemAdmin();
    }

    /** @test */
    public function a_user_can_login_in_with_correct_username_and_password()
    {
        $this->assertNull(auth()->user());

        $this->post('login', [
            'username' => $this->systemAdmin->username,
            'password' => '111111'
        ]);

        $this->assertNotNull(auth()->user());

        auth()->logout();

        $this->assertNull(auth()->user());

        $this->post('login', [
            'username' => $this->systemAdmin->email,
            'password' => '111111'
        ]);

        $this->assertNotNull(auth()->user());

        auth()->logout();
        $this->assertNull(auth()->user());

        $this->post('login', [
            'username' => $this->systemAdmin->phone_number,
            'password' => '111111'
        ]);

        $this->assertNotNull(auth()->user());
    }

    /** @test */
    public function a_user_can_not_login_in_with_incorrect_username_or_password()
    {
        $this->withExceptionHandling();
        
        $this->assertNull(auth()->user());

        $this->post('login', [
            'username' => $this->systemAdmin->email,
            'password' => 'invalid_password'
        ]);

        $this->assertNull(auth()->user());
    }
}
