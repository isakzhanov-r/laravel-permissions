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
     * @param  string  $permissions
     *
     * @return mixed
     */
    public function handle($request, Closure $next, string $permissions)
    {
        $this->abortGuest();

        if ($this->matchAllow($request, $permissions)) {
            return $next($request);
        }
        throw new AccessDeniedHttpException('User has not got a permissions', null, 403);
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
