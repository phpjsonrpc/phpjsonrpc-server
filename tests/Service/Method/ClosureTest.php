<?php

namespace PhpJsonRpc\Server\Service\Method;

use PhpJsonRpc\Server\Request\Params;
use PhpJsonRpc\Server\Service\Method\Callables\CallableClosure;

class ClosureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group method
     */
    public function it_should_call_method_1_properly_without_args()
    {
        $method = new Closure(
            'Company.Namespace.method_1',
            new CallableClosure(
                function() {
                    return 'result of method_1';
                }
            )
        );

        $this->assertEquals($method->run(), 'result of method_1');
    }

    /**
     * @test
     * @group method
     */
    public function it_should_call_method_2_properly_with_params_object()
    {
        $method = new Closure(
            'Company.Namespace.method_2',
            new CallableClosure(
                function(Params $params) {
                    return "result of method_2: {$params->get('name')}, {$params->get('age')}";
                }
            )
        );

        $params = [
            'name'  => 'Adam',
            'age'   => 29
        ];

        $this->assertEquals($method->run(new Params((object)$params)), 'result of method_2: Adam, 29');
    }
}
