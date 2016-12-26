<?php

namespace PhpJsonRpc\Server\Service\Method;

use PhpJsonRpc\Server\Request\Params;
use PhpJsonRpc\Server\Service\Method\Callables\CallableClassObject;
use PhpJsonRpc\Server\Service\Method\Exception\NotCallable;

class Method extends AbstractMethod
{
    /**
     * @var CallableClassObject
     */
    protected $callableClassObject;

    /**
     * @param string $rpcMethodName
     * @param CallableClassObject $callableClass
     */
    public function __construct($rpcMethodName, CallableClassObject $callableClass)
    {
        parent::__construct($rpcMethodName);

        $this->callableClassObject = $callableClass;
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
        $callable               = $this->callableClassObject()->assembleWithParams($params);
        $paramsBuilderClosure   = $this->callableClassObject()->paramsBuilderClosure();

        if (!is_callable($callable)) {
            throw new NotCallable($this->rpcMethodName());
        }

        if ($paramsBuilderClosure) {
            return call_user_func_array($callable, $paramsBuilderClosure($params));
        }

        return call_user_func($callable);
    }

    /**
     * @return CallableClassObject
     */
    public function callableClassObject()
    {
        return $this->callableClassObject;
    }
}
