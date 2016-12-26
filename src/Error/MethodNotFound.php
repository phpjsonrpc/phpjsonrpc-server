<?php

namespace PhpJsonRpc\Server\Error;

use PhpJsonRpc\Server\Error\Exception\ExceptionWithData;

class MethodNotFound extends ExceptionWithData
{
    protected $message  = 'Method not found';
    protected $code     = -32601;
}
