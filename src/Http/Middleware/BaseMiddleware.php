<?php

namespace IsakzhanovR\Permissions\Http\Middleware;

use Illuminate\Support\Facades\Auth;

abstract class BaseMiddleware
{
    protected function abortGuest()
    {
        abort_if(Auth::guest(), 403, 'User is not authorized.');
    }
}
