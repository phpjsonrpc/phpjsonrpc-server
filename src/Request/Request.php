<?php

namespace PhpJsonRpc\Server\Request;

use PhpJsonRpc\Server\Error\ParseError;
use PhpJsonRpc\Server\Error\InvalidRequest;

class Request extends AbstractRequest
{
    /**
     * @var string|int|null
     */
    protected $id;

    /**
     * @param \stdClass $jsonRpcMessage
     *
     * @throws InvalidRequest
     * @throws ParseError
     */
    public function __construct(\stdClass $jsonRpcMessage)
    {
        parent::__construct($jsonRpcMessage);

        $this->id = $jsonRpcMessage->id;
    }

    /**
     * @return bool
     */
    public function isNotification()
    {
        return false;
    }

    /**
     * @return int|string
     */
    public function id()
    {
        return $this->id;
    }
}
