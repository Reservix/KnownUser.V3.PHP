<?php

namespace KnownUser\Models;

class StateInfo
{
    public $isValid;
    public $queueId;
    public $isStateExtendable;
    //used just for unit tests
    public $expires;

    public function __construct($isValid, $queueId, $isStateExtendable, $expires)
    {
        $this->isValid = $isValid;
        $this->queueId = $queueId;
        $this->isStateExtendable = $isStateExtendable;
        $this->expires = $expires;
    }
}
