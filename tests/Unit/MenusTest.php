<?php

namespace Tests\Unit;

use App\Menu;
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
}
