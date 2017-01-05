<?php

namespace PhpJsonRpc\Server\Response\Contract;

use PhpJsonRpc\Server\Response\Error;

interface ErrorFormatter
{
    /**
     * @param Error $error
     *
     * @return \stdClass
     */
    public function formatError(Error $error);
}
