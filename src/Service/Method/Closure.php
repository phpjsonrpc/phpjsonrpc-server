<?php

namespace PhpJsonRpc\Server\Service\Method;

use PhpJsonRpc\Server\Request\Params;
use PhpJsonRpc\Server\Service\Method\Callables\CallableClosure;

class Closure extends AbstractMethod
{
    /**
     * @var CallableClosure
     */
    protected $closure;

    /**
     * @param string $rpcMethodName
     * @param CallableClosure $closure
     */
    public function __construct($rpcMethodName, CallableClosure $closure)
    {
        parent::__construct($rpcMethodName);

        $this->closure = $closure;
    }

    /**
     * @param Params|null $params
     *
     * @return mixed
     */
    public function run(Params $params = null)
    {
        $callable = $this->callableClosure()->assemble();

        return call_user_func_array($callable, [$params]);
    }

    /**
     * @return CallableClosure
     */
    public function callableClosure()
    {
        return $this->closure;
    }
}
