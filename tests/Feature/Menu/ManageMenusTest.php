<?php

namespace Tests\Feature;

use App\Modules\Menu\Menu;
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
    public function an_authorized_user_can_view_all_menus_in_pagination()
    {
        $this->signIn($this->systemAdmin);

        create(Menu::class, 5);

        $response = $this->get('menus?pageSize=20')
            ->assertStatus(200)
            ->json();

        $this->assertCount(6, $response['data']);

        create(Menu::class, 20);

        $response = $this->get('menus?pageSize=20')
            ->assertStatus(200)
            ->json();

        $this->assertCount(20, $response['data']);
        $this->assertEquals(26, $response['total']);
    }

    /** @test */
    public function an_unauthorized_user_cannot_view_menus()
    {
        $this->signIn()->withExceptionHandling();

        create(Menu::class, 5);

        $this->get('menus')
            ->assertStatus(403);
    }
}
