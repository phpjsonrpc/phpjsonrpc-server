<?php

namespace PhpJsonRpc\Server\Service\Method;

use PhpJsonRpc\Server\Request\Params;
use PhpJsonRpc\Server\Stub\ValueObject\Age;
use PhpJsonRpc\Server\Stub\ValueObject\Name;
use PhpJsonRpc\Server\Stub\JsonRpcController;
use PhpJsonRpc\Server\Stub\ValueObject\CustomParams;
use PhpJsonRpc\Server\Service\Method\Callables\CallableClassObject;

class MethodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group method
     */
    public function it_should_call_method_1_properly_without_args()
    {
        $method = new Method(
            'Company.Namespace.method_1',
            new CallableClassObject(
                function() {
                    return new JsonRpcController;
                },
                'method_1'
            )
        );

        $this->assertEquals($method->run(), 'result of method_1');
    }

    /**
     * @test
     * @group method
     */
    public function it_should_call_method_2_properly_without_type_hinted_args()
    {
        $method = new Method(
            'Company.Namespace.method_2',
            new CallableClassObject(
                function() {
                    return new JsonRpcController;
                },
                'method_2',
                function(Params $params) {
                    return [$params->get('name'), $params->get('age')];
                }
            )
        );

        $params = [
            'name'  => 'Adam',
            'age'   => 29
        ];

        $this->assertEquals($method->run(new Params((object)$params)), 'result of method_2: Adam, 29');
    }

    /**
     * @test
     * @group method
     */
    public function it_should_call_method_3_properly_with_type_hinted_args()
    {
        $method = new Method(
            'Company.Namespace.method_3',
            new CallableClassObject(
                function() {
                    return new JsonRpcController;
                },
                'method_3',
                function(Params $params) {
                    return [new Name($params->get('name')), new Age($params->get('age'))];
                }
            )
        );

        $params = [
            'name'  => 'Adam',
            'age'   => 29
        ];

        $this->assertEquals($method->run(new Params((object)$params)), 'result of method_3: Adam, 29');
    }

    /**
     * @test
     * @group method
     */
    public function it_should_call_method_4_properly_with_params_object()
    {
        $method = new Method(
            'Company.Namespace.method_4',
            new CallableClassObject(
                function() {
                    return new JsonRpcController;
                },
                'method_4',
                function(Params $params) {
                    return [$params];
                }
            )
        );

        $params = [
            'name'  => 'Adam',
            'age'   => 29
        ];

        $this->assertEquals($method->run(new Params((object)$params)), 'result of method_4: Adam, 29');
    }

    /**
     * @test
     * @group method
     */
    public function it_should_call_method_5_properly_with_custom_params_object()
    {
        $method = new Method(
            'Company.Namespace.method_5',
            new CallableClassObject(
                function() {
                    return new JsonRpcController;
                },
                'method_5',
                function(Params $params) {
                    return [new CustomParams($params)];
                }
            )
        );

        $params = [
            'name'  => 'Adam',
            'age'   => 29
        ];

        $this->assertEquals($method->run(new Params((object)$params)), 'result of method_5: Adam, 29');
    }

    /**
     * @test
     * @group method
     */
    public function it_should_call_method_6_properly_which_provides_a_runtime_exception()
    {
        $method = new Method(
            'Company.Namespace.method_6',
            new CallableClassObject(
                function() {
                    return new JsonRpcController;
                },
                'method_6'
            )
        );

        $this->setExpectedException('\RuntimeException');

        $method->run();
    }

    /**
     * @test
     * @group method
     */
    public function it_should_call_a_non_existing_method()
    {
        $method = new Method(
            'Company.Namespace.method_0',
            new CallableClassObject(
                function() {
                    return new JsonRpcController;
                },
                'method_0'
            )
        );

        $this->setExpectedException('\PhpJsonRpc\Server\Service\Method\Exception\NotCallable');

        $method->run();
    }
}
