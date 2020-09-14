<?php

namespace IsakzhanovR\Permissions\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Permission extends BaseMiddleware
{
    /**
     * Checks for the entry of one of the specified permissions
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array  ...$permissions
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        $this->abortGuest();

        if ($request->user()->hasPermissions($permissions)) {
            return $next($request);
        }
        throw new AccessDeniedHttpException('User has not got a permissions', null, 403);
    }
}
