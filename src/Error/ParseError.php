<?php

namespace PhpJsonRpc\Server\Error;

use PhpJsonRpc\Server\Error\Exception\ExceptionWithData;

class ParseError extends ExceptionWithData
{
    protected $message  = 'Parse error';
    protected $code     = -32700;
}
