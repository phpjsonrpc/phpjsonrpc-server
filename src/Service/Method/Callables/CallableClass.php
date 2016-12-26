<?php

namespace PhpJsonRpc\Server\Service\Method\Callables;

class CallableClass
{
    /**
     * @var callable
     */
    protected $fullyQualifiedClassName;

    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var callable|null
     */
    protected $paramsBuilderClosure = null;

    /**
     * @param string $fullyQualifiedClassName
     * @param string $methodName
     * @param callable|null $paramsBuilderClosure
     */
    public function __construct($fullyQualifiedClassName, $methodName, callable $paramsBuilderClosure = null)
    {
        $this->fullyQualifiedClassName  = $fullyQualifiedClassName;
        $this->methodName               = $methodName;
        $this->paramsBuilderClosure     = $paramsBuilderClosure;
    }

    /**
     * @return callable
     */
    public function assemble()
    {
        return [
            $this->fullyQualifiedClassName(),
            $this->methodName()
        ];
    }

    /**
     * @return callable
     */
    public function fullyQualifiedClassName()
    {
        return $this->fullyQualifiedClassName;
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
