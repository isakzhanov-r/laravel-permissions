<?php

namespace IsakzhanovR\Permissions\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Role
{
    public function handle($request, Closure $next, ...$roles)
    {
        $this->abortGuest();

        if ($this->allowUser($request, $roles)) {
            return $next($request);
        }
        throw new AccessDeniedHttpException('User has not got a role', null, 403);
    }

    protected function abortGuest()
    {
        abort_if(Auth::guest(), 403, 'User is not authorized.');
    }

    protected function allowUser($request, $permissions): bool
    {
        return $request->user()->hasRoles($permissions);
    }
}
