<?php

namespace App\Modules\Role;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return user()->can('add_role');
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('对不起，您没有更新角色权限!');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'string|max:255',
            'description' => 'string|max:511',
        ];
    }
}
