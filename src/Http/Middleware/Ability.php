<?php

namespace IsakzhanovR\Permissions\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Ability
{
    public function handle($request, Closure $next, string $permissions)
    {
        $this->abortGuest();

        if ($this->matchAllow($request, $permissions)) {
            return $next($request);
        }
        throw new AccessDeniedHttpException('User has not got a permissions', null, 403);
    }

    protected function abortGuest()
    {
        abort_if(Auth::guest(), 403, 'User is not authorized.');
    }

    protected function matchAllow($request, $permissions)
    {
        foreach ($request->user()->getPermissions() as $permission) {
            if (Str::is($permissions, $permission->slug)) {
                return true;
            }
        }

        return false;
    }
}
