<?php

namespace PhpJsonRpc\Server\Error;

use PhpJsonRpc\Server\Error\Exception\ExceptionWithData;

class InternalError extends ExceptionWithData
{
    protected $message  = 'Internal error';
    protected $code     = -32603;
}
