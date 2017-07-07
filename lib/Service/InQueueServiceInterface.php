<?php
/**
 * Created by PhpStorm.
 * User: attila
 * Date: 30.06.17
 * Time: 11:01
 */

namespace Reservix\Core\QueueBundle\Service;

use Reservix\Core\QueueBundle\Models\EventConfig;

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
