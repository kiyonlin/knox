<?php

namespace App\Modules\User;

use App\Modules\Module\Module;
use App\Modules\Role\Role;
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
     * 获取用户的所有模块
     *
     * @return Collection
     */
    public function modules()
    {
        return Module::all()->filter(function ($module) {
            return $this->can("module.view.{$module->id}");
        });
    }

    /**
     * 获取用户的所有权限名称
     *
     * @return mixed
     */
    public function permissions()
    {
        return $this->roles->reduce(function ($permissions, $role) {
            return $permissions->merge($role->perms);
        }, new Collection([]))->pluck('name');
    }

    /**
     * 获取用户可以分配的角色
     * @param string $query
     * @return Collection
     */
    public function optionalRoles(string $query)
    {
        $roleIds = $this->roles->pluck('id')->toArray();

        return Role::where('display_name', 'like', "%{$query}%")
            ->get(['id', 'name', 'display_name', 'description'])
            ->reject(function ($role) use ($roleIds) {
                return in_array($role->id, $roleIds);
            })
            ->values();
    }
}
