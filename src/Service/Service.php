<?php

namespace PhpJsonRpc\Server\Service;

use PhpJsonRpc\Server\Response\Error;
use PhpJsonRpc\Server\Request\Request;
use PhpJsonRpc\Server\Error\MethodNotFound;
use PhpJsonRpc\Server\Request\RequestBuilder;
use PhpJsonRpc\Server\Request\AbstractRequest;
use PhpJsonRpc\Server\Response\AbstractResponse;
use PhpJsonRpc\Server\Request\NotificationRequest;
use PhpJsonRpc\Server\Response\SuccessfulResponse;
use PhpJsonRpc\Server\Response\UnsuccessfulResponse;
use PhpJsonRpc\Server\Service\Method\Contract\Method;
use PhpJsonRpc\Server\Response\Contract\ErrorFormatter;
use PhpJsonRpc\Server\Response\ErrorFormatter\DefaultErrorFormatter;

class Service
{
    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var ErrorFormatter
     */
    protected $errorFormatter;

    /**
     * @var Method[]
     */
    protected $methods = [];

    /**
     * @param string $endpoint
     * @param ErrorFormatter|null $errorFormatter
     */
    public function __construct($endpoint, ErrorFormatter $errorFormatter = null)
    {
        $this->endpoint         = $endpoint;
        $this->errorFormatter   = $errorFormatter ? $errorFormatter : new DefaultErrorFormatter;
    }

    /**
     * @param string $message
     *
     * @return AbstractResponse[]|AbstractResponse|null
     *
     * @throws \Exception
     */
    public function dispatch($message)
    {
        try {
            $requestBuilder = new RequestBuilder($message);
        } catch (\Exception $e) {
            return new UnsuccessfulResponse(null, new Error($e, $this->errorFormatter));
        }

        if ($requestBuilder->isBatchRequest()) {
            return $this->buildResponses($requestBuilder);
        }

        try {
            $request = $requestBuilder->buildRequest($requestBuilder->decodedJson());
        } catch (\Exception $e) {
            return new UnsuccessfulResponse(null, new Error($e, $this->errorFormatter));
        }

        if ($request instanceof Request) {
            try {
                return $this->buildResponse($request);
            } catch (\Exception $e) {
                return new UnsuccessfulResponse($request->id(), new Error($e, $this->errorFormatter));
            }
        }
    }

    /**
     * @param Method $method
     */
    public function add(Method $method)
    {
        $this->methods[$method->rpcMethodName()] = $method;
    }

    /**
     * @return string
     */
    public function endpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return Method[]
     */
    public function methods()
    {
        return $this->methods;
    }

    /**
     * @param string $methodName
     *
     * @return Method
     *
     * @throws MethodNotFound
     */
    public function findMethodBy($methodName)
    {
        if (!array_key_exists($methodName, $this->methods())) {
            throw new MethodNotFound;
        }

        return $this->methods()[$methodName];
    }

    /**
     * @param RequestBuilder $requestBuilder
     *
     * @return AbstractResponse[]|null
     */
    protected function buildResponses(RequestBuilder $requestBuilder)
    {
        $responses = [];

        foreach ($requestBuilder->decodedJson() as $messageObject) {
            try {
                $request = $requestBuilder->buildRequest($messageObject);
            } catch (\Exception $e) {
                $responses[] = new UnsuccessfulResponse(null, new Error($e, $this->errorFormatter));
                continue;
            }

            if ($request instanceof Request) {
                try {
                    $responses[] = $this->buildResponse($request);
                } catch (\Exception $e) {
                    $responses[] = new UnsuccessfulResponse($request->id(), new Error($e, $this->errorFormatter));
                }
            }
        }

        return $responses ? : null;
    }

    /**
     * @param AbstractRequest $request
     *
     * @return null|SuccessfulResponse
     *
     * @throws \Exception
     */
    protected function buildResponse(AbstractRequest $request)
    {
        try {
            $method         = $this->findMethodBy($request->method());
            $methodResult   = $method->run($request->params());
        } catch (\Exception $e) {
            if ($request instanceof NotificationRequest) {
                return null;
            }

            throw $e;
        }

        if ($request instanceof Request) {
            return new SuccessfulResponse($request->id(), $methodResult);
        }

        return null;
    }
}
