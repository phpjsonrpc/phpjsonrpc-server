<?php

namespace PhpJsonRpc\Server\Response;

class UnsuccessfulResponse extends AbstractResponse
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var Error
     */
    protected $error;

    /**
     * @param int|null $id
     * @param Error $error
     */
    public function __construct($id, Error $error)
    {
        $this->id       = $id;
        $this->error    = $error;
    }

    /**
     * @return int|null
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return Error
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        $jsonSerializable = new \stdClass();

        $jsonSerializable->jsonrpc  = $this->jsonrpc();
        $jsonSerializable->id       = $this->id();
        $jsonSerializable->error    = $this->error();

        return $jsonSerializable;
    }
}
