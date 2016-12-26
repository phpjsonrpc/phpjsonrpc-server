<?php

namespace PhpJsonRpc\Server\Service\Method;

use PhpJsonRpc\Server\Request\Params;
use PhpJsonRpc\Server\Service\Method\Contract;
use PhpJsonRpc\Server\Service\Method\Exception\NotCallable;

abstract class AbstractMethod implements Contract\Method
{
    /**
     * @var string
     */
    protected $rpcMethodName;

    /**
     * @param string $rpcMethodName
     */
    public function __construct($rpcMethodName)
    {
        $this->rpcMethodName = $rpcMethodName;
    }

    /**
     * @param Params|null $params
     *
     * @return mixed
     *
     * @throws NotCallable
     */
    abstract public function run(Params $params = null);

    /**
     * @return string
     */
    public function rpcMethodName()
    {
        return $this->rpcMethodName;
    }
}
