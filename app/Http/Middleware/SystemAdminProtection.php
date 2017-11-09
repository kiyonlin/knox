<?php

namespace App\Http\Middleware;

use App\Modules\Module\Module;
use App\Modules\Role\Role;
use App\Modules\User\User;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

class SystemAdminProtection
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        throw_if(
            $request->method() != 'GET' && $request->method() != 'PATCH' && (
                optional($request->route('user'))->username == User::SYSTEM_ADMIN
                || optional($request->route('role'))->name == Role::SYSTEM_ADMIN
                || optional($request->route('module'))->key == Module::SYSTEM_MODULE
                || optional(optional($request->route('module'))->parentModule)->key == Module::SYSTEM_MODULE
            ),
            AuthorizationException::class
        );

        return $next($request);
    }
}
