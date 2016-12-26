<?php

namespace PhpJsonRpc\Server\Error;

use PhpJsonRpc\Server\Error\Exception\ExceptionWithData;

class InvalidParams extends ExceptionWithData
{
    protected $message  = 'Invalid params';
    protected $code     = -32602;
}
