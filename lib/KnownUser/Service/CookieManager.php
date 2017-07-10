<?php

namespace KnownUser\Service;

use KnownUser\Repository\CookieManagerInterface;

class CookieManager implements CookieManagerInterface
{
    public function getCookie($cookieName)
    {
        if (array_key_exists($cookieName, $_COOKIE)) {
            return $_COOKIE[$cookieName];
        } else {
            return null;
        }
    }

    public function setCookie($name, $value, $expire, $domain)
    {
        if ($domain == null) {
            $domain = "";
        }
        setcookie($name, $value, $expire, "/", $domain, false, true);
    }
}
