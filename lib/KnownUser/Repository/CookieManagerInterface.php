<?php

namespace KnownUser\Repository;

interface CookieManagerInterface
{
    public function setCookie($name, $value, $expire, $domain);

    public function getCookie($cookieName);
}
