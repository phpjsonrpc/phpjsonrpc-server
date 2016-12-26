<?php

namespace PhpJsonRpc\Server\Stub;

use PhpJsonRpc\Server\Request\Params;
use PhpJsonRpc\Server\Stub\ValueObject\Age;
use PhpJsonRpc\Server\Stub\ValueObject\Name;
use PhpJsonRpc\Server\Stub\ValueObject\CustomParams;

class JsonRpcController
{
    public function method_1()
    {
        return 'result of method_1';
    }

    public function method_2($name, $age)
    {
        return "result of method_2: {$name}, {$age}";
    }

    public function method_3(Name $name, Age $age)
    {
        return "result of method_3: {$name->toNative()}, {$age->toNative()}";
    }

    public function method_4(Params $params)
    {
        return "result of method_4: {$params->get('name')}, {$params->get('age')}";
    }

    public function method_5(CustomParams $params)
    {
        return "result of method_5: {$params->name()}, {$params->age()}";
    }

    public function method_6()
    {
        throw new \RuntimeException('Something is wrong!');
    }
}
