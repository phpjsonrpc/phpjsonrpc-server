<?php

namespace PhpJsonRpc\Server\Error;

use PhpJsonRpc\Server\Error\Exception\ExceptionWithData;

class InvalidRequest extends ExceptionWithData
{
    protected $message  = 'Invalid Request';
    protected $code     = -32600;
}
