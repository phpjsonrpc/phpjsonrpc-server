<?php

namespace PhpJsonRpc\Server\Service;

use PhpJsonRpc\Server\Request\Params;
use PhpJsonRpc\Server\Service\Method\Method;
use PhpJsonRpc\Server\Service\Method\Closure;
use PhpJsonRpc\Server\Stub\JsonRpcController;
use PhpJsonRpc\Server\Response\SuccessfulResponse;
use PhpJsonRpc\Server\Response\UnsuccessfulResponse;
use PhpJsonRpc\Server\Service\Method\Callables\CallableClosure;
use PhpJsonRpc\Server\Service\Method\Callables\CallableClassObject;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group service
     */
    public function it_should_has_the_given_endpoint()
    {
        $service = new Service('json-rpc-2.0/v1');

        $this->assertEquals('json-rpc-2.0/v1', $service->endpoint());
    }

    /**
     * @test
     * @group service
     */
    public function it_should_find_the_given_method()
    {
        $service = new Service('json-rpc-2.0/v1');

        $this->assertCount(0, $service->methods());

        $service->add(
            new Method(
                'Company.Namespace.method_1',
                new CallableClassObject(
                    function() {
                        return new JsonRpcController;
                    },
                    'method_1'
                )
            )
        );

        $this->assertCount(1, $service->methods());
        $this->assertInstanceOf(
            '\PhpJsonRpc\Server\Service\Method\Contract\Method',
            $service->findMethodBy('Company.Namespace.method_1')
        );
    }

    /**
     * @test
     * @group service
     */
    public function it_should_have_the_proper_response()
    {
        $service = new Service('json-rpc-2.0/v1');

        $service->add(
            new Closure(
                'Company.Namespace.method_1',
                new CallableClosure(
                    function() {
                        return 'result of method_1';
                    }
                )
            )
        );

        $this->assertEquals(
            'result of method_1',
            $service->dispatch(
                '{"jsonrpc": "2.0", "method": "Company.Namespace.method_1", "id": 1}'
            )->result()
        );
    }

    /**
     * @test
     * @group service
     */
    public function it_should_handle_a_batch_request_with_2_success_responses()
    {
        $service = new Service('json-rpc-2.0/v1');

        $service->add(
            new Closure(
                'Company.Namespace.method_1',
                new CallableClosure(
                    function() {
                        return 'result of method_1';
                    }
                )
            )
        );
        $service->add(
            new Closure(
                'Company.Namespace.method_2',
                new CallableClosure(
                    function() {
                        return 'result of method_2';
                    }
                )
            )
        );

        /** @var SuccessfulResponse[] $responses */
        $responses = $service->dispatch(
            '[
                {"jsonrpc": "2.0", "method": "Company.Namespace.method_1", "id": 1},
                {"jsonrpc": "2.0", "method": "Company.Namespace.method_2", "id": 2}
            ]'
        );

        $this->assertCount(2, $responses);
        $this->assertEquals('result of method_1', $responses[0]->result());
        $this->assertEquals('result of method_2', $responses[1]->result());
    }

    /**
 * @test
 * @group service
 */
    public function it_should_throw_an_invalid_request_to_an_empty_array()
    {
        $service = new Service('json-rpc-2.0/v1');

        $this->setExpectedException('PhpJsonRpc\Server\Error\InvalidRequest');

        $service->dispatch(
            '[]'
        );
    }

    /**
     * @test
     * @group service
     */
    public function it_should_return_with_invalid_request_errors_to_an_array_1_2_3()
    {
        $service = new Service('json-rpc-2.0/v1');

        /** @var UnsuccessfulResponse[] $responses */
        $responses = $service->dispatch(
            '[1,2,3]'
        );

        $this->assertCount(3, $responses);
        $this->assertEquals('Invalid Request', $responses[0]->error()->message());
        $this->assertEquals('Invalid Request', $responses[1]->error()->message());
        $this->assertEquals('Invalid Request', $responses[2]->error()->message());
    }

    /**
     * @test
     * @group service
     */
    public function it_should_response_properly_to_a_big_batch_request()
    {
        $service = new Service('json-rpc-2.0/v1');

        $service->add(
            new Closure(
                'Company.Namespace.method_1',
                new CallableClosure(
                    function(Params $params) {
                        return 'result of method_1 ' . $params->get('a');
                    }
                )
            )
        );

        $responses = $service->dispatch(
            '[
                1,
                2,
                {"jsonrpc": "2.0", "method": "Company.Namespace.method_1", "params": {"a": 5}, "id": 1},
                4,
                {"jsonrpc": "2.0", "method": "Company.Namespace.method_2", "id": 2},
                {"jsonrpc": "2.0", "method": "Company.Namespace.method_2"},
                6
            ]'
        );

        $this->assertCount(6, $responses);
        /** @var UnsuccessfulResponse[] $responses */
        $this->assertEquals('Invalid Request', $responses[0]->error()->message());
        $this->assertEquals(null, $responses[0]->id());
        $this->assertEquals('Invalid Request', $responses[1]->error()->message());
        $this->assertEquals(null, $responses[1]->id());
        /** @var SuccessfulResponse[] $responses */
        $this->assertEquals('result of method_1 5', $responses[2]->result());
        $this->assertEquals(1, $responses[2]->id());
        /** @var UnsuccessfulResponse[] $responses */
        $this->assertEquals('Invalid Request', $responses[3]->error()->message());
        $this->assertEquals(null, $responses[3]->id());
        $this->assertEquals('Method not found', $responses[4]->error()->message());
        $this->assertEquals(2, $responses[4]->id());
        $this->assertEquals('Invalid Request', $responses[5]->error()->message());
        $this->assertEquals(null, $responses[5]->id());
    }

    /**
     * @test
     * @group service
     */
    public function it_should_have_no_response_because_the_batch_request_contains_only_notifications()
    {
        $service = new Service('json-rpc-2.0/v1');

        $service->add(
            new Closure(
                'Company.Namespace.method_1',
                new CallableClosure(
                    function() {
                        return 'result of method_1';
                    }
                )
            )
        );

        $response = $service->dispatch(
            '[
                {"jsonrpc": "2.0", "method": "Company.Namespace.method_1"},
                {"jsonrpc": "2.0", "method": "Company.Namespace.method_1"},
                {"jsonrpc": "2.0", "method": "Company.Namespace.method_1"}
            ]'
        );

        $this->assertEquals(null, $response);
    }

    /**
     * @test
     * @group service
     */
    public function it_should_not_response_to_a_notification_request()
    {
        $service = new Service('json-rpc-2.0/v1');

        $service->add(
            new Closure(
                'Company.Namespace.method_1',
                new CallableClosure(
                    function() {
                        return 'result of method_1';
                    }
                )
            )
        );

        $this->assertNull($service->dispatch(
            '{"jsonrpc": "2.0", "method": "Company.Namespace.method_1"}'
        ));
    }

    /**
     * @test
     * @group service
     */
    public function it_should_blow_up_with_parse_error()
    {
        $service = new Service('json-rpc-2.0/v1');

        $this->setExpectedException('\PhpJsonRpc\Server\Error\ParseError');

        $service->dispatch('bla bla bla');
    }

    /**
     * @test
     * @group service
     */
    public function it_should_blow_up_with_invalid_request()
    {
        $service = new Service('json-rpc-2.0/v1');

        $this->setExpectedException('\PhpJsonRpc\Server\Error\InvalidRequest');

        $service->dispatch(
            '{"jsonrpc": "2.0", "metXhod": "welcome", "params": {"name": "Adam", "age": 29}, "id": 1}'
        );
    }

    /**
     * @test
     * @group service
     */
    public function it_should_blow_up_with_method_not_found()
    {
        $service = new Service('json-rpc-2.0/v1');

        $this->setExpectedException('\PhpJsonRpc\Server\Error\MethodNotFound');

        $service->dispatch(
            '{"jsonrpc": "2.0", "method": "welcome", "params": {"name": "Adam", "age": 29}, "id": 1}'
        );
    }
}
