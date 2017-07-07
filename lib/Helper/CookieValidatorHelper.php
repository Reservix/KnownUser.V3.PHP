<?php

namespace Reservix\Core\QueueBundle\Helper;

class CookieValidatorHelper
{
    public static function evaluate(array $triggerPart, array $cookieList)
    {
        if (!array_key_exists("Operator", $triggerPart) || !array_key_exists(
                "IsNegative",
                $triggerPart
            ) || !array_key_exists("IsIgnoreCase", $triggerPart) || !array_key_exists(
                "ValueToCompare",
                $triggerPart
            ) || !array_key_exists("CookieName", $triggerPart)
        ) {
            return false;
        }

        $cookieValue = "";
        $cookieName = $triggerPart["CookieName"];
        if ($cookieName !== null && array_key_exists($cookieName, $cookieList) && $cookieList[$cookieName] !== null) {
            $cookieValue = $cookieList[$cookieName];
        }

        return ComparisonOperatorHelper::evaluate(
            $triggerPart["Operator"],
            $triggerPart["IsNegative"],
            $triggerPart["IsIgnoreCase"],
            $cookieValue,
            $triggerPart["ValueToCompare"]
        );
    }
}
