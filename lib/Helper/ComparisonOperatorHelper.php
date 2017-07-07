<?php

namespace Reservix\Core\QueueBundle\Helper;

class ComparisonOperatorHelper
{
    public static function evaluate($opt, $isNegative, $isIgnoreCase, $left, $right)
    {
        $left = !is_null($left) ? $left : "";
        $right = !is_null($right) ? $right : "";

        switch ($opt) {
            case "Equals":
                return ComparisonOperatorHelper::equals($left, $right, $isNegative, $isIgnoreCase);
            case "Contains":
                return ComparisonOperatorHelper::contains($left, $right, $isNegative, $isIgnoreCase);
            case "StartsWith":
                return ComparisonOperatorHelper::startsWith($left, $right, $isNegative, $isIgnoreCase);
            case "EndsWith":
                return ComparisonOperatorHelper::endsWith($left, $right, $isNegative, $isIgnoreCase);
            case "MatchesWith":
                return ComparisonOperatorHelper::matchesWith($left, $right, $isNegative, $isIgnoreCase);
            default:
                return false;
        }
    }

    private static function contains($left, $right, $isNegative, $ignoreCase)
    {
        if ($right === "*") {
            return true;
        }

        if ($ignoreCase) {
            $left = strtoupper($left);
            $right = strtoupper($right);
        }
        $evaluation = strpos($left, $right) !== false;
        if ($isNegative) {
            return !$evaluation;
        } else {
            return $evaluation;
        }
    }

    private static function equals($left, $right, $isNegative, $ignoreCase)
    {
        if ($ignoreCase) {
            $left = strtoupper($left);
            $right = strtoupper($right);
        }
        $evaluation = $left === $right;

        if ($isNegative) {
            return !$evaluation;
        } else {
            return $evaluation;
        }
    }

    private static function endsWith($left, $right, $isNegative, $ignoreCase)
    {
        if ($ignoreCase) {
            $left = strtoupper($left);
            $right = strtoupper($right);
        }
        $evaluation = false;
        $rLength = strlen($right);
        if ($rLength === 0) {
            $evaluation = true;
        } else {
            $evaluation = substr($left, -$rLength) === $right;
        }

        if ($isNegative) {
            return !$evaluation;
        } else {
            return $evaluation;
        }
    }

    private static function startsWith($left, $right, $isNegative, $ignoreCase)
    {
        if ($ignoreCase) {
            $left = strtoupper($left);
            $right = strtoupper($right);
        }
        $evaluation = false;

        $rLength = strlen($right);
        $evaluation = (substr($left, 0, $rLength) === $right);

        if ($isNegative) {
            return !$evaluation;
        } else {
            return $evaluation;
        }
    }

    private static function matchesWith($left, $right, $isNegative, $ignoreCase)
    {
        if ($ignoreCase) {
            $left = strtoupper($left);
            $right = strtoupper($right);
        }

        if (preg_match($right, $left)) {
            $evaluation = true;
        } else {
            $evaluation = false;
        }

        if ($isNegative) {
            return !$evaluation;
        } else {
            return $evaluation;
        }
    }
}
