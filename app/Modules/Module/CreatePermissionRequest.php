<?php

namespace App\Modules\Module;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class CreatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 系统模块的子模块下不允许添加新的权限

        return user()->can('permission.add')
            && Module::SYSTEM_MODULE != optional(request()->route('module')->parentModule)->key;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => 'string|max:255|unique:permissions',
            'display_name' => 'nullable|string|max:255',
            'description'  => 'nullable|string|max:511',
        ];
    }
}
