<?php

namespace PhpJsonRpc\Server\Response\ErrorFormatter;

use PhpJsonRpc\Server\Response\Error;
use PhpJsonRpc\Server\Response\Contract\ErrorFormatter;

class DefaultErrorFormatter implements ErrorFormatter
{
    /**
     * @var bool
     */
    protected $isDebug;

    /**
     * @param bool $isDebug
     */
    public function __construct($isDebug = true)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * @param Error $error
     *
     * @return \stdClass
     */
    public function formatError(Error $error)
    {
        $jsonSerializable = new \stdClass();

        $jsonSerializable->code     = $error->code();
        $jsonSerializable->message  = $error->message();

        if ($error->hasData()) {
            $jsonSerializable->data = $error->data();
        }

        if ($this->isDebug) {
            $jsonSerializable->debug = $this->buildDebugData($error->exception());
        }

        return $jsonSerializable;
    }

    /**
     * @param \Exception $exception
     *
     * @return array
     */
    protected function buildDebugData(\Exception $exception)
    {
        $debugData = [
            'code'      => $exception->getCode(),
            'message'   => $exception->getMessage(),
            'type'      => get_class($exception),
            'file'      => $exception->getFile(),
            'line'      => $exception->getLine(),
            'trace'     => $exception->getTrace()
        ];

        if ($exception->getPrevious()) {
            $debugData  = [$debugData];
            $newError   = $this->buildDebugData($exception->getPrevious());

            array_unshift($debugData, $newError);
        }

        return $debugData;
    }
}
