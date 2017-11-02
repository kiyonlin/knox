<?php

namespace App\Modules\User;

use App\Modules\Menu\Menu;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Parsidev\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'display_name', 'phone_number', 'password', 'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $with = [];

    /**
     * 获取用户的所有菜单
     *
     * @return Collection
     */
    public function menus()
    {
        return Menu::all()->filter(function($menu){
            return $this->can("view_menu_{$menu->id}");
        });
    }

    /**
     * 获取用户的所有权限名称
     *
     * @return mixed
     */
    public function permissions()
    {
        return $this->roles->reduce(function ($permissions, $role){
            return $permissions->merge($role->perms);
        }, new Collection([]))->pluck('name');
    }
}
