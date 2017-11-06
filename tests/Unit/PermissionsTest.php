<?php

namespace Tests\Unit;

use App\Modules\Permission\Permission;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionsTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_will_take_name_as_display_name_when_it_is_null()
    {
        $permission = create(Permission::class);
        $this->assertEquals($permission->name, $permission->display_name);

        $anotherPermission = create(Permission::class, ['display_name' => 'display_name']);
        $this->assertNotEquals($anotherPermission->name, $anotherPermission->display_name);
    }
}
