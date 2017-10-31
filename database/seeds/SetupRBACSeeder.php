<?php

use App\Models\Menu;
use App\Models\RBAC\Permission;
use App\Models\RBAC\Role;
use App\Models\User;
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

        $this->setUpInitialMenu($systemAdminRole);
    }

    /**
     * 系统管理员角色可以管理用户、管理角色、管理权限、菜单管理
     *
     * @param $systemAdminRole
     */
    private function setUpInitialPermissions(Role $systemAdminRole)
    {
        $systemAdminRole->attachPermissions([
            create(Permission::class, [
                'name'         => 'add_user',
                'display_name' => '添加用户',
                'description'  => '添加一个新用户'
            ]),

            create(Permission::class, [
                'name'         => 'delete_user',
                'display_name' => '删除用户',
                'description'  => '删除一个已存在的用户'
            ]),

            create(Permission::class, [
                'name'         => 'update_user',
                'display_name' => '更新用户',
                'description'  => '更新一个已存在的用户'
            ]),

            create(Permission::class, [
                'name'         => 'view_user',
                'display_name' => '查看用户',
                'description'  => '查看一个已存在的用户'
            ]),

            create(Permission::class, [
                'name'         => 'add_role',
                'display_name' => '添加角色',
                'description'  => '添加一个新角色'
            ]),

            create(Permission::class, [
                'name'         => 'delete_role',
                'display_name' => '删除角色',
                'description'  => '删除一个已存在的角色'
            ]),

            create(Permission::class, [
                'name'         => 'update_role',
                'display_name' => '更新角色',
                'description'  => '更新一个已存在的角色'
            ]),

            create(Permission::class, [
                'name'         => 'view_role',
                'display_name' => '查看角色',
                'description'  => '查看一个已存在的角色'
            ]),

            create(Permission::class, [
                'name'         => 'add_permission',
                'display_name' => '添加权限',
                'description'  => '添加一个新权限'
            ]),

            create(Permission::class, [
                'name'         => 'delete_permission',
                'display_name' => '删除权限',
                'description'  => '删除一个已存在的权限'
            ]),

            create(Permission::class, [
                'name'         => 'update_permission',
                'display_name' => '更新权限',
                'description'  => '更新一个已存在的权限'
            ]),

            create(Permission::class, [
                'name'         => 'view_permission',
                'display_name' => '查看权限',
                'description'  => '查看一个已存在的权限'
            ]),

            create(Permission::class, [
                'name'         => 'add_menu',
                'display_name' => '添加菜单',
                'description'  => '添加一个新菜单'
            ]),

            create(Permission::class, [
                'name'         => 'delete_menu',
                'display_name' => '删除菜单',
                'description'  => '删除一个已存在的菜单'
            ]),

            create(Permission::class, [
                'name'         => 'update_menu',
                'display_name' => '更新菜单',
                'description'  => '更新一个已存在的菜单'
            ]),

            create(Permission::class, [
                'name'         => 'view_menu',
                'display_name' => '查看菜单',
                'description'  => '查看一个已存在的菜单'
            ]),
        ]);
    }

    /**
     * 初始化菜单权限
     *
     * @param $systemAdminRole
     */
    private function setUpInitialMenu(Role $systemAdminRole)
    {
        $systemManagerMenu = create(Menu::class, [
            'key'  => 'system_manager',
            'name' => '系统管理',
            'icon' => 'el-icon-setting'
        ]);

        $userManagerMenu = create(Menu::class, [
            'pid'  => $systemManagerMenu->id,
            'key'  => 'user_manager',
            'name' => '用户管理',
            'path' => '/users',
            'icon' => 'el-icon-info'
        ]);

        $roleManagerMenu = create(Menu::class, [
            'pid'  => $systemManagerMenu->id,
            'key'  => 'role_manager',
            'name' => '角色管理',
            'path' => '/roles',
            'icon' => 'el-icon-tickets'
        ]);

        $permissionManagerMenu = create(Menu::class, [
            'pid'  => $systemManagerMenu->id,
            'key'  => 'permission_manager',
            'name' => '权限管理',
            'path' => '/permissions',
            'icon' => 'el-icon-success'
        ]);

        $menuManagerMenu = create(Menu::class, [
            'pid'  => $systemManagerMenu->id,
            'key'  => 'menu_manager',
            'name' => '菜单管理',
            'path' => '/menus',
            'icon' => 'el-icon-menu'
        ]);

        $systemAdminRole->attachPermissions([
            create(Permission::class, [
                'name'         => "view_menu_{$systemManagerMenu->id}",
                'display_name' => "查看{$systemManagerMenu->name}菜单",
                'description'  => "查看{$systemManagerMenu->name}菜单"
            ]),

            create(Permission::class, [
                'name'         => "view_menu_{$userManagerMenu->id}",
                'display_name' => "查看{$userManagerMenu->name}菜单",
                'description'  => "查看{$userManagerMenu->name}菜单"
            ]),

            create(Permission::class, [
                'name'         => "view_menu_{$roleManagerMenu->id}",
                'display_name' => "查看{$roleManagerMenu->name}菜单",
                'description'  => "查看{$roleManagerMenu->name}菜单"
            ]),

            create(Permission::class, [
                'name'         => "view_menu_{$permissionManagerMenu->id}",
                'display_name' => "查看{$permissionManagerMenu->name}菜单",
                'description'  => "查看{$permissionManagerMenu->name}菜单"
            ]),

            create(Permission::class, [
                'name'         => "view_menu_{$menuManagerMenu->id}",
                'display_name' => "查看{$menuManagerMenu->name}菜单",
                'description'  => "查看{$menuManagerMenu->name}菜单"
            ]),

        ]);
    }
}
