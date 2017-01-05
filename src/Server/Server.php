<?php

namespace PhpJsonRpc\Server\Server;

use PhpJsonRpc\Server\Service\Service as JsonRpcService;

/**
 * Dummy server.
 */
class Server
{
    /**
     * @var JsonRpcService[]
     */
    protected $services = [];

    /**
     * @param JsonRpcService $jsonRpcService
     */
    public function addService(JsonRpcService $jsonRpcService)
    {
        $this->services[$jsonRpcService->endpoint()] = $jsonRpcService;
    }

    /**
     * @param string $endpoint
     * @param string $request
     */
    public function handle($endpoint, $request)
    {
        echo json_encode(
            $this->findService($endpoint)->dispatch($request)
        );
    }

    /**
     * @return JsonRpcService[]
     */
    public function services()
    {
        return $this->services;
    }

    /**
     * @param string $endpoint
     *
     * @return JsonRpcService
     *
     * @throws \Exception
     */
    public function findService($endpoint)
    {
        if (!array_key_exists($endpoint, $this->services())) {
            throw new \Exception("Endpoint <<{$endpoint}>> is not found!");
        }

        return $this->services()[$endpoint];
    }
}
