<?php

namespace PhpJsonRpc\Server\Server\Booboo;

use League\BooBoo\Formatter\AbstractFormatter;
use PhpJsonRpc\Server\Error\Exception\ExceptionWithData;

class JsonFormatter extends AbstractFormatter
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;
    }

    /**
     * @param \Exception $e
     *
     * @return string
     */
    public function format(\Exception $e)
    {
        if ($e instanceof \ErrorException) {
            $data = $this->handleErrors($e);
        } else {
            $data = $this->formatExceptions($e);
        }

        return json_encode($data);
    }

    public function handleErrors(\ErrorException $e)
    {
        $error = [
            'type'      => $this->determineSeverityTextValue($e->getSeverity()),
            'message'   => $e->getMessage(),
        ];

        if ($this->debug) {
            $error['debug'] = [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => $e->getTrace()
            ];
        }

        return $error;
    }

    /**
     * @param \Exception $e
     *
     * @return array
     */
    protected function formatExceptions(\Exception $e)
    {
        $error = [
            'type'      => get_class($e),
            'message'   => $e->getMessage()
        ];

        if ($e instanceof ExceptionWithData) {
            $error['data'] = $e->getData();
        }

        if ($this->debug) {
            $error['debug'] = [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => $e->getTrace()
            ];
        }

        if ($e->getPrevious()) {
            $error      = [$error];
            $newError   = $this->formatExceptions($e->getPrevious());

            array_unshift($error, $newError);
        }

        return $error;
    }
}
