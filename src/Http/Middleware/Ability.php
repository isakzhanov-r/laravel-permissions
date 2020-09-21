<?php

namespace IsakzhanovR\Permissions\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Ability extends BaseMiddleware
{
    /**
     * Checks for matching permissions *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $placeholder
     *
     * @return mixed
     */
    public function handle($request, Closure $next, string $placeholder)
    {
        $this->abortGuest();

        if ($this->matchAllowRoles($request, $placeholder) || $this->matchAllowPermissions($request, $placeholder)) {
            return $next($request);
        }
        throw new AccessDeniedHttpException('User has not got a permissions', null, 403);
    }

    protected function matchAllowRoles($request, $placeholder)
    {
        $placeholder = e(trim($placeholder));
        foreach ($request->user()->roles as $role) {
            if (Str::is($placeholder, $role->slug)) {
                return true;
            }
        }

        return false;
    }

    protected function matchAllowPermissions($request, $permissions)
    {
        return $request->user()->matchPermissions($permissions);
    }
}
