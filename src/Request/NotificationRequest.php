<?php

namespace PhpJsonRpc\Server\Request;

class NotificationRequest extends AbstractRequest
{
    /**
     * @return bool
     */
    public function isNotification()
    {
        return true;
    }
}
