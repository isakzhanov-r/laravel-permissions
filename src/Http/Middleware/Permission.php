<?php

namespace IsakzhanovR\Permissions\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        $this->abortGuest();

        if ($this->allowUser($request, $permissions)) {
            return $next($request);
        }
        throw new AccessDeniedHttpException('User has not got a permissions', null, 403);
    }

    protected function abortGuest()
    {
        abort_if(Auth::guest(), 403, 'User is not authorized.');
    }

    protected function allowUser($request, $permissions): bool
    {
        return $request->user()->hasPermissions($permissions);
    }
}
