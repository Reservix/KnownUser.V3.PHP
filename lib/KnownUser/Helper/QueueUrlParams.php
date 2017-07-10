<?php

namespace KnownUser\Helper;

class QueueUrlParams
{
    const TIMESTAMPKEY = "ts";
    const EXTENDABLECOOKIEKEY = "ce";
    const COOKIEVALIDITYMINUTEKEY = "cv";
    const HASHKEY = "h";
    const EVENTIDKEY = "e";
    const QUEUEIDKEY = "q";
    const KEYVALUESEPARATORCHAR = '_';
    const KEYVALUESEPARATORGROUPCHAR = '~';

    public $timeStamp = 0;
    public $eventId = "";
    public $hashCode = "";
    public $extendableCookie = false;
    public $cookieValidityMinute = null;
    public $queueITToken = "";
    public $queueITTokenWithoutHash = "";
    public $queueId = "";

    public static function extractQueueParams($queueitToken)
    {

        $result = new QueueUrlParams();
        $result->queueITToken = $queueitToken;
        $paramsNameValueList = explode(QueueUrlParams::KEYVALUESEPARATORGROUPCHAR, $result->queueITToken);

        foreach ($paramsNameValueList as $pNameValue) {
            $paramNameValueArr = explode(QueueUrlParams::KEYVALUESEPARATORCHAR, $pNameValue);

            switch ($paramNameValueArr[0]) {
                case QueueUrlParams::TIMESTAMPKEY:
                    if (is_numeric($paramNameValueArr[1])) {
                        $result->timeStamp = intval($paramNameValueArr[1]);
                    } else {
                        $result->timeStamp = 0;
                    }
                    break;
                case QueueUrlParams::COOKIEVALIDITYMINUTEKEY:
                    if (is_numeric($paramNameValueArr[1])) {
                        $result->cookieValidityMinute = intval($paramNameValueArr[1]);
                    }
                    break;
                case QueueUrlParams::EVENTIDKEY:
                    $result->eventId = $paramNameValueArr[1];
                    break;
                case QueueUrlParams::EXTENDABLECOOKIEKEY:
                    $result->extendableCookie = $paramNameValueArr[1] === 'True' || $paramNameValueArr[1] === 'true';
                    break;
                case QueueUrlParams::HASHKEY:
                    $result->hashCode = $paramNameValueArr[1];
                    break;
                case QueueUrlParams::QUEUEIDKEY:
                    $result->queueId = $paramNameValueArr[1];
                    break;
            }
        }
        $result->queueITTokenWithoutHash = str_replace(
            QueueUrlParams::KEYVALUESEPARATORGROUPCHAR . QueueUrlParams::HASHKEY .
            QueueUrlParams::KEYVALUESEPARATORCHAR . $result->hashCode,
            "",
            $result->queueITToken
        );

        return $result;
    }
}
