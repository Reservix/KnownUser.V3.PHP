<?php
/**
 * Created by PhpStorm.
 * User: attila
 * Date: 30.06.17
 * Time: 11:04
 */

namespace Reservix\Core\QueueBundle\Repository;

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
