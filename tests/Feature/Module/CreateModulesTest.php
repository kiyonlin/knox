<?php

namespace Tests\Feature;

use App\Modules\Module\Module;
use App\Modules\Role\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateModulesTest extends TestCase
{

    const TABLE_NAME = 'modules';
    use RefreshDatabase;

    protected $systemAdmin;

    public function setUp()
    {
        parent::setUp();

        $this->systemAdmin = Role::whereName('systemAdmin')->first()->users()->first();
    }

    /** @test */
    public function an_authorized_user_can_add_a_new_module()
    {
        $this->signIn($this->systemAdmin);

        $newModule = raw(Module::class);

        $this->postJson('modules', $newModule);

        $this->assertDatabaseHas(self::TABLE_NAME, array_except($newModule, ['index']));
    }

    /** @test */
    public function an_unauthorized_user_can_not_add_a_new_module()
    {
        // 登录一个没有新增模块权限的用户
        $this->signIn()->withExceptionHandling();

        $newModule = raw(Module::class);

        $this->post('modules', $newModule)
            ->assertStatus(403);
    }

    /** @test */
    public function a_module_may_have_a_valid_pid()
    {
        $this->addModule(['pid' => null])
            ->assertSessionMissing('pid');

        $this->addModule(['pid' => 'invalid'])
            ->assertSessionHasErrors('pid');
    }

    /** @test */
    public function a_module_requires_a_valid_key()
    {
        $this->addModule(['key' => null])
            ->assertSessionHasErrors('key');

        $this->addModule(['key' => str_random(256)])
            ->assertSessionHasErrors('key');

        $this->addModule(['key' => Module::first()->key])
            ->assertSessionHasErrors('key');
    }

    /** @test */
    public function a_module_may_have_a_valid_path()
    {
        $this->addModule(['path' => null])
            ->assertSessionMissing('path');

        $this->addModule(['path' => str_random(256)])
            ->assertSessionHasErrors('path');
    }

    /** @test */
    public function a_module_requires_a_valid_name()
    {
        $this->addModule(['name' => null])
            ->assertSessionHasErrors('name');

        $this->addModule(['name' => str_random(256)])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_module_may_have_a_valid_icon()
    {
        $this->addModule(['icon' => null])
            ->assertSessionMissing('icon');

        $this->addModule(['icon' => str_random(256)])
            ->assertSessionHasErrors('icon');
    }

    /** @test */
    public function a_module_may_have_a_valid_sort()
    {
        $this->addModule(['sort' => null])
            ->assertSessionMissing('sort');

        $this->addModule(['sort' => 'invalid'])
            ->assertSessionHasErrors('sort');
    }

    /**
     * 测试新增模块时每个校验字段的通用函数
     *
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function addModule($overrides = [])
    {
        $this->signIn($this->systemAdmin)
            ->withExceptionHandling();

        return $this->post('modules', raw(Module::class, $overrides));
    }

    /** @test */
    public function an_authorized_user_can_update_modules()
    {
        // 'pid' => 'nullable|numeric',
        //     'name' => 'required|string|max:255',
        //     'path' => 'nullable|string|max:255|unique:modules',
        //     'icon' => 'nullable|string|max:255',
        //     'sort' => 'nullable|numeric',
        $this->updateModule(['pid' => Module::where('pid', '<>', 0)->first()->pid]);
        $this->updateModule(['name' => 'new_name']);
        $this->updateModule(['path' => '/new_path']);
        $this->updateModule(['icon' => 'new_icon']);
        $this->updateModule(['sort' => 2]);
    }

    private function updateModule($patch)
    {
        $this->signIn($this->systemAdmin);

        $module = create(Module::class);

        $this->patch("modules/{$module->id}", $patch)
            ->assertStatus(204);

        $this->assertDatabaseHas('modules',
            array_merge($patch, ['id' => $module->id])
        );
    }

    /** @test */
    public function a_module_should_have_a_valid_pid()
    {
        $this->updateInvalidModule(['pid' => str_random(1)])
            ->assertSessionHasErrors('pid');
    }

    /** @test */
    public function a_module_should_have_a_valid_name()
    {
        $this->addModule(['name' => str_random(512)])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_module_should_have_a_valid_path()
    {
        $this->addModule(['path' => str_random(512)])
            ->assertSessionHasErrors('path');
    }

    /** @test */
    public function a_module_should_have_a_valid_icon()
    {
        $this->addModule(['icon' => str_random(256)])
            ->assertSessionHasErrors('icon');
    }

    /** @test */
    public function a_module_should_have_a_valid_sort()
    {
        $this->updateInvalidModule(['sort' => str_random(1)])
            ->assertSessionHasErrors('sort');
    }

    /**
     * 测试新增用户时每个校验字段的通用函数
     *
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function updateInvalidModule($overrides = [])
    {
        $this->signIn($this->systemAdmin)
            ->withExceptionHandling();

        return $this->post('modules', raw(Module::class, $overrides));
    }

    /** @test */
    public function an_unauthorized_user_cannot_update_modules()
    {
        $this->signIn()->withExceptionHandling();

        $this->patch("modules/{$this->systemAdmin->id}", ['name' => 'new_name'])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authorized_user_can_delete_a_module()
    {
        $this->signIn($this->systemAdmin);

        $module = create(Module::class);

        $this->delete("modules/{$module->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('modules', $module->toArray());
    }

    /** @test */
    public function an_unauthorized_user_can_not_delete_a_module()
    {
        $this->signIn();

        $module = create(Module::class);

        $this->deleteJson("modules/{$module->id}")
            ->assertStatus(403);
    }
}
