<?php

namespace KnownUser\Service;

use KnownUser\Models\EventConfig;

interface InQueueServiceInterface
{
    public function validateRequest(
        $currentPageUrl,
        $queueitToken,
        EventConfig $config,
        $customerId,
        $secretKey
    );

    public function cancelQueueCookie($eventId, $cookieDomain);

    public function extendQueueCookie(
        $eventId,
        $cookieValidityMinute,
        $cookieDomain,
        $secretKey
    );
}
