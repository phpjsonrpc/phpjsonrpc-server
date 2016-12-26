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

class Service
{
    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var Method[]
     */
    protected $methods = [];

    /**
     * @param string $endpoint
     */
    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @param string $message
     * @return AbstractResponse[]|SuccessfulResponse|null
     *
     * @throws \Exception
     */
    public function dispatch($message)
    {
        $requestBuilder = new RequestBuilder($message);

        if ($requestBuilder->isBatchRequest()) {
            $responses = [];

            foreach ($requestBuilder->decodedJson() as $messageObject) {
                try {
                    $request = $requestBuilder->buildRequest($messageObject);
                } catch (\Exception $e) {
                    $responses[] = new UnsuccessfulResponse(null, Error::create($e));
                    continue;
                }

                if ($request instanceof Request) {
                    try {
                        $responses[] = $this->buildResponse($request);
                    } catch (\Exception $e) {
                        $responses[] = new UnsuccessfulResponse($request->id(), Error::create($e));
                    }
                }
            }

            return $responses ? : null;
        }

        return $this->buildResponse(
            $requestBuilder->buildRequest($requestBuilder->decodedJson())
        );
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

        return null; # notification request
    }
}
