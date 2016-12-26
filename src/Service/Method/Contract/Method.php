<?php

namespace PhpJsonRpc\Server\Service\Method\Contract;

use PhpJsonRpc\Server\Request\Params;
use PhpJsonRpc\Server\Service\Method\Exception\NotCallable;

interface Method
{
    /**
     * @return string
     */
    public function rpcMethodName();

    /**
     * @param Params|null $params
     *
     * @return mixed
     *
     * @throws NotCallable
     */
    public function run(Params $params = null);
}
