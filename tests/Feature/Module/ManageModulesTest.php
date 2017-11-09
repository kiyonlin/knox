<?php

namespace Tests\Feature;

use App\Modules\Module\Module;
use App\Modules\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManegeModulesTest extends TestCase
{

    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = User::systemAdmin();
    }

    /** @test */
    public function an_authorized_user_can_view_all_modules_in_pagination()
    {
        $this->signIn($this->systemAdmin);

        create(Module::class, 5);

        $response = $this->get('modules?pageSize=20')
            ->assertStatus(200)
            ->json();

        $this->assertCount(7, $response['data']);

        create(Module::class, 20);

        $response = $this->get('modules?pageSize=20')
            ->assertStatus(200)
            ->json();

        $this->assertCount(20, $response['data']);
        $this->assertEquals(27, $response['total']);
    }

    /** @test */
    public function an_unauthorized_user_cannot_view_modules()
    {
        $this->signIn()->withExceptionHandling();

        $this->get('modules')
            ->assertStatus(403);
    }

    /** @test */
    public function an_authorized_user_can_view_all_top_modules()
    {
        $this->signIn($this->systemAdmin);

        create(Module::class, 5);

        $response = $this->get('modules/tops')
            ->assertStatus(200)
            ->json();

        $this->assertCount(6, $response);
    }

    /** @test */
    public function an_unauthorized_user_cannot_view_top_modules()
    {
        $this->signIn()->withExceptionHandling();

        $this->get('modules/tops')
            ->assertStatus(403);
    }
}
