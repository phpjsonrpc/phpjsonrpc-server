<?php

namespace PhpJsonRpc\Server\Server;

use League\BooBoo\Runner as BooBooRunner;
use PhpJsonRpc\Server\Server\Booboo\JsonFormatter;
use PhpJsonRpc\Server\Service\Service as JsonRpcService;

/**
 * Dummy server.
 */
class Server
{
    /**
     * @var JsonRpcService[]
     */
    private $services = [];

    /**
     * @var BooBooRunner
     */
    private $boobooRunner;

    /**
     * @param BooBooRunner|null $boobooRunner
     */
    public function __construct(BooBooRunner $boobooRunner = null)
    {
        $this->boobooRunner = $boobooRunner ? : new BooBooRunner([new JsonFormatter(true)]);
    }

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
        $this->boobooRunner->treatErrorsAsExceptions(true);
        $this->boobooRunner->register();

        echo json_encode(
            $this->findService($endpoint)->dispatch($request)
        );

        $this->boobooRunner->clearFormatters();
        $this->boobooRunner->clearHandlers();
        $this->boobooRunner->deregister();
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
