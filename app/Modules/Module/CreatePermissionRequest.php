<?php

namespace App\Modules\Module;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return user()->can('add_permission');
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('对不起，您没有新增权限的权限!');
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
