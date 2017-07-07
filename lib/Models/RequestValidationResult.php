<?php

namespace Reservix\Core\QueueBundle\Models;

use Reservix\Core\QueueBundle\Utils\Utils;

class RequestValidationResult
{
    public $eventId;
    public $redirectUrl;
    public $queueId;

    public function __construct($eventId, $queueId, $redirectUrl)
    {
        $this->eventId = $eventId;
        $this->queueId = $queueId;
        $this->redirectUrl = $redirectUrl;
    }

    public function doRedirect()
    {
        return !Utils::isNullOrEmptyString($this->redirectUrl);
    }
}
