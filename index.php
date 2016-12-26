<?php

require 'vendor/autoload.php';


//$service = new \PhpJsonRpc\Server\Service\Service('json-rpc-2.0/v1');
//
//$service->dispatch(
//    '{"jsonrpc": "2.0", "metXhod": "welcome", "params": {"name": "Adam", "age": 29}, "id": 1}'
//);
//
//return;


$server = new \PhpJsonRpc\Server\Server\Server();

$service = new \PhpJsonRpc\Server\Service\Service('json-rpc-2.0/v1');
$service->add(
    new \PhpJsonRpc\Server\Service\Method\Closure(
        'Company.Namespace.method_1',
        new \PhpJsonRpc\Server\Service\Method\Callables\CallableClosure(function() {
            //throw new \Exception('ajjaj');
            throw new \PhpJsonRpc\Server\Error\Exception\ExceptionWithData('ok');

            $a = new stdClass();
            $a->b;

            return 'hali';
        })
    )
);

$server->addService($service);

//try {
    $server->handle(
        'json-rpc-2.0/v1',
        '{"jsonrpc": "2.0", "method": "Company.Namespace.method_1", "id": 1}'
    );
//} catch (\Exception $e) {
//    //echo 'hi';
//    throw $e;
//}


//        $runner = new Runner([new JsonFormatter()]);
//        $runner->treatErrorsAsExceptions(true);
//        $runner->register();