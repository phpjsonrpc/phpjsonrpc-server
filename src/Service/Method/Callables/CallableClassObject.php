<?php

namespace PhpJsonRpc\Server\Service\Method\Callables;

use PhpJsonRpc\Server\Request\Params;

class CallableClassObject
{
    /**
     * @var callable
     */
    protected $objectBuilderClosure;

    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var callable|null
     */
    protected $paramsBuilderClosure;

    /**
     * @param callable $objectBuilderClosure
     * @param string $methodName
     * @param callable|null $paramsBuilderClosure
     */
    public function __construct(callable $objectBuilderClosure, $methodName, callable $paramsBuilderClosure = null)
    {
        $this->objectBuilderClosure = $objectBuilderClosure;
        $this->methodName           = $methodName;
        $this->paramsBuilderClosure = $paramsBuilderClosure;
    }

    /**
     * @param Params|null $params
     *
     * @return callable
     */
    public function assembleWithParams(Params $params = null)
    {
        return [
            call_user_func($this->objectBuilderClosure(), [$params]),
            $this->methodName()
        ];
    }

    /**
     * @return callable
     */
    public function objectBuilderClosure()
    {
        return $this->objectBuilderClosure;
    }

    /**
     * @return string
     */
    public function methodName()
    {
        return $this->methodName;
    }

    /**
     * @return callable|null
     */
    public function paramsBuilderClosure()
    {
        return $this->paramsBuilderClosure;
    }
}
