<?php

namespace PhpJsonRpc\Server\Request;

use PhpJsonRpc\Server\Error\ParseError;
use PhpJsonRpc\Server\Error\InvalidRequest;

abstract class AbstractRequest
{
    /**
     * @var string
     */
    protected $jsonrpc;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var null|Params
     */
    protected $params;

    /**
     * @param \stdClass $jsonRpcMessage
     *
     * @throws InvalidRequest
     * @throws ParseError
     */
    public function __construct(\stdClass $jsonRpcMessage)
    {
        $this->jsonrpc  = $jsonRpcMessage->jsonrpc;
        $this->method   = $jsonRpcMessage->method;

        if (property_exists($jsonRpcMessage, 'params')) {
            $this->params = new Params($jsonRpcMessage->params);
        }
    }

    /**
     * @return bool
     */
    abstract public function isNotification();

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
    public function method()
    {
        return $this->method;
    }

    /**
     * @return null|Params
     */
    public function params()
    {
        return $this->params;
    }
}
