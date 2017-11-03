<?php

namespace Tests\Unit;

use App\Modules\Menu\Menu;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenusTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_knows_its_index()
    {
        $menu = create(Menu::class);
        $this->assertEquals($menu->id, $menu->index);

        $subMenu = create(Menu::class, ['pid' => $menu->id]);
        $this->assertEquals("{$menu->id}-{$subMenu->id}", $subMenu->index);

        $subSubMenu = create(Menu::class, ['pid' => $subMenu->id]);
        $this->assertEquals("{$menu->id}-{$subMenu->id}-{$subSubMenu->id}", $subSubMenu->index);
    }

    /** @test */
    public function it_knows_its_level()
    {
        $menu = create(Menu::class);
        $this->assertEquals(1, $menu->level);

        $subMenu = create(Menu::class, ['pid' => $menu->id]);
        $this->assertEquals(2, $subMenu->level);

        $subSubMenu = create(Menu::class, ['pid' => $subMenu->id]);
        $this->assertEquals(3, $subSubMenu->level);
    }

    /** @test */
    public function it_knows_if_it_is_leaf()
    {
        $menu = create(Menu::class);
        $this->assertTrue($menu->is_leaf);

        $subMenu = create(Menu::class, ['pid' => $menu->id]);
        $this->assertFalse($menu->fresh()->is_leaf);
        $this->assertTrue($subMenu->is_leaf);
    }

    /** @test */
    public function it_may_have_some_submenus()
    {
        $menu = create(Menu::class);
        $this->assertInstanceOf(Collection::class, $menu->subMenus);

        create(Menu::class, ['pid' => $menu->id], 3);

        $this->assertCount(3, $menu->fresh()->subMenus);
    }
}
