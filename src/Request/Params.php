<?php

namespace PhpJsonRpc\Server\Request;

class Params
{
    /**
     * @var \stdClass|array $params
     */
    protected $params;

    /**
     * @param \stdClass|array $params
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * @param string|int $identifier
     *
     * @return mixed
     */
    public function get($identifier)
    {
        if (is_array($this->params)) {
            return $this->params[$identifier];
        }

        return $this->params->{$identifier};
    }

    /**
     * @return array|\stdClass
     */
    public function all()
    {
        return $this->params;
    }
}
