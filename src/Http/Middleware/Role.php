<?php

namespace IsakzhanovR\Permissions\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Role extends BaseMiddleware
{
    /**
     * Checks for the occurrence of one of the specified roles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array  ...$roles
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $this->abortGuest();

        if ($request->user()->hasRoles($roles)) {
            return $next($request);
        }
        throw new AccessDeniedHttpException('User has not got a role', null, 403);
    }
}
