<?php

namespace KnownUser\Repository;

interface InQueueStateRepositoryInterface
{
    public function store(
        $eventId,
        $queueId,
        $isStateExtendable,
        $cookieValidityMinute,
        $cookieDomain,
        $customerSecretKey
    );

    public function getState($eventId, $secretKey);

    public function cancelQueueCookie($eventId, $cookieDomain);

    public function extendQueueCookie($eventId, $cookieValidityMinute, $cookieDomain, $secretKey);
}
