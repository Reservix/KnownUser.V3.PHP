<?php

namespace Reservix\Core\QueueBundle\Helper;

class UrlValidatorHelper
{
    public static function evaluate(array $triggerPart, $url)
    {
        if (!array_key_exists("Operator", $triggerPart) || !array_key_exists(
                "IsNegative",
                $triggerPart
            ) || !array_key_exists("IsIgnoreCase", $triggerPart) || !array_key_exists(
                "ValueToCompare",
                $triggerPart
            ) || !array_key_exists("UrlPart", $triggerPart)
        ) {
            return false;
        }

        return ComparisonOperatorHelper::Evaluate(
            $triggerPart["Operator"],
            $triggerPart["IsNegative"],
            $triggerPart["IsIgnoreCase"],
            UrlValidatorHelper::getUrlPart($triggerPart["UrlPart"], $url),
            $triggerPart["ValueToCompare"]
        );
    }

    private static function getUrlPart($urlPart, $url)
    {
        $urlParts = parse_url($url);

        switch ($urlPart) {
            case "PagePath":
                return $urlParts['path'];
            case "PageUrl":
                return $url;
            case "HostName":
                return $urlParts['host'];
            default:
                return "";
        }
    }
}
