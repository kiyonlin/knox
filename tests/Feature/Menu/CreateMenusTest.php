<?php

namespace Tests\Feature;

use App\Modules\Menu\Menu;
use App\Modules\Role\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateMenusTest extends TestCase
{

    const TABLE_NAME = 'menus';
    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = Role::whereName('systemAdmin')->first()->users()->first();
    }

    /** @test */
    public function an_authorized_user_can_add_a_new_menu()
    {
        $this->signIn($this->systemAdmin);

        $newMenu = raw(Menu::class);

        $this->postJson('menus', $newMenu);

        $this->assertDatabaseHas(self::TABLE_NAME, array_except($newMenu, ['index']));
    }

    /** @test */
    public function an_unauthorized_user_can_not_add_a_new_menu()
    {
        // 登录一个没有新增菜单权限的用户
        $this->signIn()->withExceptionHandling();

        $newMenu = raw(Menu::class);

        $this->post('menus', $newMenu)
            ->assertStatus(403);
    }

    /** @test */
    public function a_menu_may_have_a_valid_pid()
    {
        $this->addMenu(['pid' => null])
            ->assertSessionMissing('pid');

        $this->addMenu(['pid' => 'invalid'])
            ->assertSessionHasErrors('pid');
    }

    /** @test */
    public function a_menu_requires_a_valid_key()
    {
        $this->addMenu(['key' => null])
            ->assertSessionHasErrors('key');

        $this->addMenu(['key' => str_random(256)])
            ->assertSessionHasErrors('key');

        $this->addMenu(['key' => Menu::first()->key])
            ->assertSessionHasErrors('key');
    }

    /** @test */
    public function a_menu_may_have_a_valid_path()
    {
        $this->addMenu(['path' => null])
            ->assertSessionMissing('path');

        $this->addMenu(['path' => str_random(256)])
            ->assertSessionHasErrors('path');

        $this->addMenu(['path' => Menu::where('path', '<>', null)->first()->path])
            ->assertSessionHasErrors('path');
    }

    /** @test */
    public function a_menu_requires_a_valid_name()
    {
        $this->addMenu(['name' => null])
            ->assertSessionHasErrors('name');

        $this->addMenu(['name' => str_random(256)])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_menu_may_have_a_valid_icon()
    {
        $this->addMenu(['icon' => null])
            ->assertSessionMissing('icon');

        $this->addMenu(['icon' => str_random(256)])
            ->assertSessionHasErrors('icon');
    }

    /** @test */
    public function a_menu_may_have_a_valid_sort()
    {
        $this->addMenu(['sort' => null])
            ->assertSessionMissing('sort');

        $this->addMenu(['sort' => 'invalid'])
            ->assertSessionHasErrors('sort');
    }

    /**
     * 测试新增菜单时每个校验字段的通用函数
     *
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function addMenu($overrides = [])
    {
        $this->signIn($this->systemAdmin)
            ->withExceptionHandling();

        return $this->post('menus', raw(Menu::class, $overrides));
    }

    /** @test */
    public function an_authorized_user_can_update_menus()
    {
        // 'pid' => 'nullable|numeric',
        //     'name' => 'required|string|max:255',
        //     'path' => 'nullable|string|max:255|unique:menus',
        //     'icon' => 'nullable|string|max:255',
        //     'sort' => 'nullable|numeric',
        $this->updateMenu(['pid' => Menu::where('pid', '<>', 0)->first()->pid]);
        $this->updateMenu(['name' => 'new_name']);
        $this->updateMenu(['path' => '/new_path']);
        $this->updateMenu(['icon' => 'new_icon']);
        $this->updateMenu(['sort' => 2]);
    }

    private function updateMenu($patch)
    {
        $this->signIn($this->systemAdmin);

        $menu = create(Menu::class);

        $this->patch("menus/{$menu->id}", $patch)
            ->assertStatus(204);

        $this->assertDatabaseHas('menus',
            array_merge($patch, ['id' => $menu->id])
        );
    }

    /** @test */
    public function a_menu_should_have_a_valid_pid()
    {
        $this->updateInvalidMenu(['pid' => str_random(1)])
            ->assertSessionHasErrors('pid');
    }

    /** @test */
    public function a_menu_should_have_a_valid_name()
    {
        $this->addMenu(['name' => str_random(512)])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_menu_should_have_a_valid_path()
    {
        $this->addMenu(['path' => str_random(512)])
            ->assertSessionHasErrors('path');

        $this->addMenu(['path' => Menu::where('path', '<>', null)->first()->path])
            ->assertSessionHasErrors('path');
    }

    /** @test */
    public function a_menu_should_have_a_valid_icon()
    {
        $this->addMenu(['icon' => str_random(256)])
            ->assertSessionHasErrors('icon');
    }

    /** @test */
    public function a_menu_should_have_a_valid_sort()
    {
        $this->updateInvalidMenu(['sort' => str_random(1)])
            ->assertSessionHasErrors('sort');
    }

    /**
     * 测试新增用户时每个校验字段的通用函数
     *
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function updateInvalidMenu($overrides = [])
    {
        $this->signIn($this->systemAdmin)
            ->withExceptionHandling();

        return $this->post('menus', raw(Menu::class, $overrides));
    }

    /** @test */
    public function an_unauthorized_user_cannot_update_menus()
    {
        $this->signIn()->withExceptionHandling();

        $this->patch("menus/{$this->systemAdmin->id}", ['name' => 'new_name'])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authorized_user_can_delete_a_menu()
    {
        $this->signIn($this->systemAdmin);

        $menu = create(Menu::class);

        $this->delete("menus/{$menu->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('menus', $menu->toArray());
    }

    /** @test */
    public function an_unauthorized_user_can_not_delete_a_menu()
    {
        $this->signIn();

        $menu = create(Menu::class);

        $this->deleteJson("menus/{$menu->id}")
            ->assertStatus(403);
    }
}
