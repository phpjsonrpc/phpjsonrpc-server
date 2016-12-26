<?php

namespace PhpJsonRpc\Server\Server;

use PhpJsonRpc\Server\Service\Service;
use PhpJsonRpc\Server\Service\Method\Closure;
use PhpJsonRpc\Server\Service\Method\Callables\CallableClosure;

class ServerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group server
     */
    public function it_should_have_a_proper_response()
    {
        $server = new Server();

        $service = new Service('json-rpc-2.0/v1');
        $service->add(
            new Closure(
                'Company.Namespace.method_1',
                new CallableClosure(function() {
                    return 'dummy server response';
                })
            )
        );

        $server->addService($service);

        ob_start();

        $server->handle(
            'json-rpc-2.0/v1',
            '{"jsonrpc": "2.0", "method": "Company.Namespace.method_1", "id": 1}'
        );

        $this->assertEquals(
            '{"jsonrpc":"2.0","id":1,"result":"dummy server response"}',
            ob_get_clean()
        );
    }
}
