<?php

namespace PhpJsonRpc\Server\Response;

class SuccessfulResponse extends AbstractResponse
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * @param int $id
     * @param mixed $result
     */
    public function __construct($id, $result)
    {
        $this->id       = $id;
        $this->result   = $result;
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        $jsonSerializable = new \stdClass();

        $jsonSerializable->jsonrpc  = $this->jsonrpc();
        $jsonSerializable->id       = $this->id();
        $jsonSerializable->result   = $this->result();

        return $jsonSerializable;
    }
}
