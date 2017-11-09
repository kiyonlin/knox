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
        // 系统模块下不能通过接口增加子模块
        $systemModuleId = Module::where('key', Module::SYSTEM_MODULE)->first()->id;

        return user()->can('module.add')
            && request('pid') != $systemModuleId;
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
