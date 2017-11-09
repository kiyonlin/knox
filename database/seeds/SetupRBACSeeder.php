<?php

use App\Modules\Module\Module;
use App\Modules\Permission\Permission;
use App\Modules\Role\Role;
use App\Modules\User\User;
use Illuminate\Database\Seeder;

class SetupRBACSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $systemAdminUser = create(User::class, [
            'phone_number' => '13675822217',
            'username'     => 'kiyon',
            'display_name' => '林脉沐',
            'email'        => 'kiyonlin@163.com'
        ]);

        $systemAdminRole = create(Role::class, [
            'name'         => 'systemAdmin',
            'display_name' => '系统管理员',
            'description'  => '系统初始用户，拥有最大权限'
        ]);

        $systemAdminUser->attachRole($systemAdminRole);

        $this->setUpInitialModule();

        $this->setUpInitialPermissions($systemAdminRole);
    }

    /**
     * 初始化模块权限
     *
     */
    private function setUpInitialModule()
    {
        $dashboardModule = create(Module::class, [
            'key'  => 'dashboard',
            'name' => '系统概览',
            'path' => '/',
            'icon' => 'el-icon-location'
        ]);

        $systemManagerModule = create(Module::class, [
            'key'  => 'system',
            'name' => '系统管理',
            'icon' => 'el-icon-setting'
        ]);

        $userManagerModule = create(Module::class, [
            'pid'  => $systemManagerModule->id,
            'key'  => 'user',
            'name' => '用户管理',
            'path' => '/users',
            'icon' => 'el-icon-info'
        ]);
        $userManagerModule->addPerms([
            [
                'name'         => 'user.add',
                'display_name' => '添加用户',
                'description'  => '添加一个新用户'
            ],

            [
                'name'         => 'user.delete',
                'display_name' => '删除用户',
                'description'  => '删除一个已存在的用户'
            ],

            [
                'name'         => 'user.update',
                'display_name' => '更新用户',
                'description'  => '更新一个已存在的用户'
            ],

            [
                'name'         => 'user.view',
                'display_name' => '查看用户',
                'description'  => '查看一个已存在的用户'
            ]
        ]);

        $roleManagerModule = create(Module::class, [
            'pid'  => $systemManagerModule->id,
            'key'  => 'role',
            'name' => '角色管理',
            'path' => '/roles',
            'icon' => 'el-icon-tickets'
        ]);
        $roleManagerModule->addPerms([
            [
                'name'         => 'role.add',
                'display_name' => '添加角色',
                'description'  => '添加一个新角色'
            ],
            [
                'name'         => 'role.delete',
                'display_name' => '删除角色',
                'description'  => '删除一个已存在的角色'
            ],
            [
                'name'         => 'role.update',
                'display_name' => '更新角色',
                'description'  => '更新一个已存在的角色'
            ],
            [
                'name'         => 'role.view',
                'display_name' => '查看角色',
                'description'  => '查看一个已存在的角色'
            ]
        ]);

        $moduleManagerModule = create(Module::class, [
            'pid'  => $systemManagerModule->id,
            'key'  => 'module',
            'name' => '模块管理',
            'path' => '/modules',
            'icon' => 'el-icon-menu'
        ]);
        $moduleManagerModule->addPerms([
            [
                'name'         => 'module.add',
                'display_name' => '添加模块',
                'description'  => '添加一个新模块'
            ],
            [
                'name'         => 'module.delete',
                'display_name' => '删除模块',
                'description'  => '删除一个已存在的模块'
            ],
            [
                'name'         => 'module.update',
                'display_name' => '更新模块',
                'description'  => '更新一个已存在的模块'
            ],
            [
                'name'         => 'module.view',
                'display_name' => '查看模块',
                'description'  => '查看一个已存在的模块'
            ],
            [
                'name'         => 'permission.add',
                'display_name' => '添加权限',
                'description'  => '添加一个新权限'
            ],
            [
                'name'         => 'permission.delete',
                'display_name' => '删除权限',
                'description'  => '删除一个已存在的权限'
            ],
            [
                'name'         => 'permission.update',
                'display_name' => '更新权限',
                'description'  => '更新一个已存在的权限'
            ],
            [
                'name'         => 'permission.view',
                'display_name' => '查看权限',
                'description'  => '查看一个已存在的权限'
            ],
        ]);
    }

    /**
     * 系统管理员角色可以管理用户、管理角色、管理权限、模块管理
     *
     * @param $systemAdminRole
     */
    private function setUpInitialPermissions(Role $systemAdminRole)
    {
        Module::all()->each(function ($module) use($systemAdminRole) {
            $systemAdminRole->attachPermissions($module->perms()->where('name', 'not like', '%.%.%')->get()->toArray());
        });
    }
}
