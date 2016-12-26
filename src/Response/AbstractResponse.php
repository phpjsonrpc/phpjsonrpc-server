<?php

namespace PhpJsonRpc\Server\Response;

abstract class AbstractResponse implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $jsonrpc = '2.0';

    /**
     * @return string
     */
    public function jsonrpc()
    {
        return $this->jsonrpc;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this, JSON_PRETTY_PRINT);
    }
}
