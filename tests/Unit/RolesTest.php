<?php

namespace Tests\Unit;

use App\Modules\Role\Role;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolesTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_may_have_some_perms()
    {
        $this->assertInstanceOf(Collection::class, create(Role::class)->perms);
    }
}
