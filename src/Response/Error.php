<?php

namespace PhpJsonRpc\Server\Response;

use PhpJsonRpc\Server\Error\Exception\ExceptionWithData;

class Error implements \JsonSerializable
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var mixed|null
     */
    protected $data;

    /**
     * @param int $code
     * @param string $message
     * @param mixed|null $data
     */
    public function __construct($code, $message, $data = null)
    {
        $this->code     = $code;
        $this->message  = $message;
        $this->data     = $data;
    }

    /**
     * @param \Exception $exception
     *
     * @return Error
     */
    public static function create(\Exception $exception)
    {
        $data = null;

        if ($exception instanceof ExceptionWithData) {
            $data = $exception->getData();
        }

        return new self($exception->getCode(), $exception->getMessage(), $data);
    }

    /**
     * @return int
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * @return mixed|null
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        $jsonSerializable = new \stdClass();

        $jsonSerializable->code     = $this->code();
        $jsonSerializable->message  = $this->message();

        if (!is_null($this->data())) {
            $jsonSerializable->data = $this->data();
        }

        return $jsonSerializable;
    }
}
