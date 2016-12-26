<?php

namespace PhpJsonRpc\Server\Service\Method;

use PhpJsonRpc\Server\Request\Params;
use PhpJsonRpc\Server\Service\Method\Callables\CallableClass;
use PhpJsonRpc\Server\Service\Method\Exception\NotCallable;

class StaticMethod extends AbstractMethod
{
    /**
     * @var CallableClass
     */
    protected $callableClass;

    /**
     * @param string $rpcMethodName
     * @param CallableClass $callableClass
     */
    public function __construct($rpcMethodName, CallableClass $callableClass)
    {
        parent::__construct($rpcMethodName);

        $this->callableClass = $callableClass;
    }

    /**
     * @param Params|null $params
     *
     * @return mixed
     *
     * @throws NotCallable
     */
    public function run(Params $params = null)
    {
        $callable               = $this->callableClass()->assemble();
        $paramsBuilderClosure   = $this->callableClass()->paramsBuilderClosure();

        if (!is_callable($callable)) {
            throw new NotCallable($this->rpcMethodName());
        }

        if ($paramsBuilderClosure) {
            return call_user_func_array($callable, $paramsBuilderClosure($params));
        }

        return call_user_func($callable);
    }

    /**
     * @return CallableClass
     */
    public function callableClass()
    {
        return $this->callableClass;
    }
}
