<?php

namespace IsakzhanovR\Permissions\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnknownKeyException extends HttpException
{
    public function __construct(string $value, $model = null)
    {
        $message = sprintf('Unknown key %s in properties %s', $value, $model);
        $code    = 500;
        parent::__construct($code, $message, null, [], $code);
    }
}
