<?php
/**
 * Created by PhpStorm.
 * User: attila
 * Date: 30.06.17
 * Time: 11:05
 */

namespace Reservix\Core\QueueBundle\Repository;

interface CookieManagerInterface
{
    public function setCookie($name, $value, $expire, $domain);

    public function getCookie($cookieName);
}
