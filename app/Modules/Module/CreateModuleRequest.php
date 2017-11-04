<?php

namespace App\Modules\Module;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateModuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return user()->can('module.add');
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('对不起，您没有新增模块权限!');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pid' => 'nullable|numeric',
            'key' => 'required|string|max:255|unique:modules',
            'name' => 'required|string|max:255',
            'path' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'sort' => 'nullable|numeric',
        ];
    }
}
