<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use SetupRBACSeeder;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication;

    public function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        Artisan::call('db:seed', ['--class' => SetUpRBACSeeder::class]);
    }

    public function signIn(User $user = null)
    {
        $user = $user ?: create(User::class);
        $this->actingAs($user);

        return $this;
    }
}
