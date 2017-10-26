<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        create(User::class, ['name' => 'kiyon', 'email' => 'kiyonlin@163.com']);
    }
}
