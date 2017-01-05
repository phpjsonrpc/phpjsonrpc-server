<?php

namespace PhpJsonRpc\Server\Response;

use PhpJsonRpc\Server\Response\Contract\ErrorFormatter;
use PhpJsonRpc\Server\Error\Exception\ExceptionWithData;

class Error implements \JsonSerializable
{
    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @var ErrorFormatter
     */
    protected $errorFormatter;

    /**
     * @param \Exception $exception
     * @param ErrorFormatter $errorFormatter
     */
    public function __construct(\Exception $exception, ErrorFormatter $errorFormatter)
    {
        $this->exception        = $exception;
        $this->errorFormatter   = $errorFormatter;
    }

    /**
     * @return \Exception
     */
    public function exception()
    {
        return $this->exception;
    }

    /**
     * @return int|mixed
     */
    public function code()
    {
        return $this->exception()->getCode();
    }

    /**
     * @return string
     */
    public function message()
    {
        return $this->exception()->getMessage();
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return ($this->exception instanceof ExceptionWithData && $this->exception->getData());
    }

    /**
     * @return mixed|null
     */
    public function data()
    {
        if ($this->exception instanceof ExceptionWithData) {
            return $this->exception->getData();
        }
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        return $this->errorFormatter->formatError($this);
    }
}
