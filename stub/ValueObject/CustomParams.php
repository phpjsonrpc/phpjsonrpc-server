<?php

namespace PhpJsonRpc\Server\Stub\ValueObject;

use PhpJsonRpc\Server\Request\Params;

class CustomParams
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $age;

    /**
     * @param Params $params
     */
    public function __construct(Params $params)
    {
        $this->name = $params->get('name');
        $this->age  = $params->get('age');
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function age()
    {
        return $this->age;
    }
}
