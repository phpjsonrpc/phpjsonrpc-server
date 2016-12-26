<?php

namespace PhpJsonRpc\Server\Service\Method\Callables;

class CallableClosure
{
    /**
     * @var callable
     */
    protected $closure;

    /**
     * @param callable $closure
     */
    public function __construct(callable $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @return callable
     */
    public function assemble()
    {
        return $this->closure();
    }

    /**
     * @return callable
     */
    public function closure()
    {
        return $this->closure;
    }
}
