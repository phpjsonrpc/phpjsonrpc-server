<?php

namespace PhpJsonRpc\Server\Error\Exception;

class ExceptionWithData extends \Exception
{
    /**
     * @var mixed|null
     */
    protected $data;

    /**
     * @param mixed|null $data
     */
    public function __construct($data = null)
    {
        parent::__construct($this->getMessage(), $this->getCode());

        $this->data = $data;
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }
}
