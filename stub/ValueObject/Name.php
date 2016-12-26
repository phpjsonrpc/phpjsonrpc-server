<?php

namespace PhpJsonRpc\Server\Stub\ValueObject;

class Name
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function toNative()
    {
        return $this->value;
    }
}
