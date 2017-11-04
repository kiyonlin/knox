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

        $this->setUpInitialPermissions($systemAdminRole);

        $this->setUpInitialModule($systemAdminRole);
    }

    /**
     * 系统管理员角色可以管理用户、管理角色、管理权限、菜单管理
     *
     * @param $systemAdminRole
     */
    private function setUpInitialPermissions(Role $systemAdminRole)
    {
        $systemAdminRole->attachPermissions([


        ]);
    }

    /**
     * 初始化菜单权限
     *
     * @param $systemAdminRole
     */
    private function setUpInitialModule(Role $systemAdminRole)
    {
        $systemManagerModule = create(Module::class, [
            'key'  => 'system_manager',
            'name' => '系统管理',
            'icon' => 'el-icon-setting'
        ]);

        $userManagerModule = create(Module::class, [
            'pid'  => $systemManagerModule->id,
            'key'  => 'user_manager',
            'name' => '用户管理',
            'path' => '/users',
            'icon' => 'el-icon-info'
        ]);
        $userManagerModule->addPerms([
            [
                'name'         => 'add_user',
                'display_name' => '添加用户',
                'description'  => '添加一个新用户'
            ],

            [
                'name'         => 'delete_user',
                'display_name' => '删除用户',
                'description'  => '删除一个已存在的用户'
            ],

            [
                'name'         => 'update_user',
                'display_name' => '更新用户',
                'description'  => '更新一个已存在的用户'
            ],

            [
                'name'         => 'view_user',
                'display_name' => '查看用户',
                'description'  => '查看一个已存在的用户'
            ]
        ]);

        $roleManagerModule = create(Module::class, [
            'pid'  => $systemManagerModule->id,
            'key'  => 'role_manager',
            'name' => '角色管理',
            'path' => '/roles',
            'icon' => 'el-icon-tickets'
        ]);
        $roleManagerModule->addPerms([
            [
                'name'         => 'add_role',
                'display_name' => '添加角色',
                'description'  => '添加一个新角色'
            ],
            [
                'name'         => 'delete_role',
                'display_name' => '删除角色',
                'description'  => '删除一个已存在的角色'
            ],
            [
                'name'         => 'update_role',
                'display_name' => '更新角色',
                'description'  => '更新一个已存在的角色'
            ],
            [
                'name'         => 'view_role',
                'display_name' => '查看角色',
                'description'  => '查看一个已存在的角色'
            ]
        ]);

        $moduleManagerModule = create(Module::class, [
            'pid'  => $systemManagerModule->id,
            'key'  => 'module_manager',
            'name' => '菜单管理',
            'path' => '/modules',
            'icon' => 'el-icon-module'
        ]);
        $moduleManagerModule->addPerms([
            [
                'name'         => 'add_module',
                'display_name' => '添加菜单',
                'description'  => '添加一个新菜单'
            ],
            [
                'name'         => 'delete_module',
                'display_name' => '删除菜单',
                'description'  => '删除一个已存在的菜单'
            ],
            [
                'name'         => 'update_module',
                'display_name' => '更新菜单',
                'description'  => '更新一个已存在的菜单'
            ],
            [
                'name'         => 'view_module',
                'display_name' => '查看菜单',
                'description'  => '查看一个已存在的菜单'
            ],
            [
                'name'         => 'add_permission',
                'display_name' => '添加权限',
                'description'  => '添加一个新权限'
            ],
            [
                'name'         => 'delete_permission',
                'display_name' => '删除权限',
                'description'  => '删除一个已存在的权限'
            ],
            [
                'name'         => 'update_permission',
                'display_name' => '更新权限',
                'description'  => '更新一个已存在的权限'
            ],
            [
                'name'         => 'view_permission',
                'display_name' => '查看权限',
                'description'  => '查看一个已存在的权限'
            ],
        ]);

        $systemAdminRole->attachPermissions(
            $userManagerModule->perms->toArray()
        );

        $systemAdminRole->attachPermissions(
            $roleManagerModule->perms->toArray()
        );

        $systemAdminRole->attachPermissions(
            $moduleManagerModule->perms->toArray()
        );
    }
}
