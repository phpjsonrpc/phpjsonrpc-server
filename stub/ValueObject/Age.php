<?php

namespace PhpJsonRpc\Server\Stub\ValueObject;

class Age
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param int $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function toNative()
    {
        return $this->value;
    }
}
