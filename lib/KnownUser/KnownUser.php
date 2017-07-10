<?php

namespace KnownUser;

use KnownUser\Exceptions\KnownUserException;
use KnownUser\Models\EventConfig;
use KnownUser\Models\RequestValidationResult;
use KnownUser\Repository\UserInQueueStateCookieRepository;
use KnownUser\Service\CookieManager;
use KnownUser\Service\IntegrationEvaluator;
use KnownUser\Service\UserInQueueService;

class KnownUser
{
    //used for unittest
    private static $userInQueueService = null;

    private static function createUserInQueueService()
    {
        if (KnownUser::$userInQueueService == null) {
            return new UserInQueueService(new UserInQueueStateCookieRepository(new CookieManager()));
        }

        return KnownUser::$userInQueueService;
    }

    public static function cancelQueueCookie($eventId, $cookieDomain)
    {
        if (empty($eventId)) {
            throw new KnownUserException("eventId can not be null or empty.");
        }

        $userInQueueService = KnownUser::createUserInQueueService();
        $userInQueueService->cancelQueueCookie($eventId, $cookieDomain);
    }

    public static function extendQueueCookie($eventId, $cookieValidityMinute, $cookieDomain, $secretKey)
    {
        if (empty($eventId)) {
            throw new KnownUserException("eventId can not be null or empty.");
        }
        if (empty($secretKey)) {
            throw new KnownUserException("secretKey can not be null or empty.");
        }
        if (!is_int($cookieValidityMinute) || intval($cookieValidityMinute) <= 0) {
            throw new KnownUserException("cookieValidityMinute should be integer greater than 0.");
        }
        $userInQueueService = KnownUser::createUserInQueueService();
        $userInQueueService->extendQueueCookie($eventId, $cookieValidityMinute, $cookieDomain, $secretKey);
    }

    public static function validateRequestByLocalEventConfig(
        $targetUrl,
        $queueitToken,
        EventConfig $eventConfig,
        $customerId,
        $secretKey
    ) {
        if (empty($customerId)) {
            throw new KnownUserException("customerId can not be null or empty.");
        }

        if (empty($secretKey)) {
            throw new KnownUserException("secretKey can not be null or empty.");
        }

        if (empty($eventConfig->eventId)) {
            throw new KnownUserException("eventId can not be null or empty.");
        }

        if (empty($eventConfig->queueDomain)) {
            throw new KnownUserException("queueDomain can not be null or empty.");
        }

        if (!is_int($eventConfig->cookieValidityMinute) || intval($eventConfig->cookieValidityMinute) <= 0) {
            throw new KnownUserException("cookieValidityMinute should be integer greater than 0.");
        }

        if (!is_bool($eventConfig->extendCookieValidity)) {
            throw new KnownUserException("extendCookieValidity should be valid boolean.");
        }

        $userInQueueService = KnownUser::createUserInQueueService();

        return $userInQueueService->validateRequest($targetUrl, $queueitToken, $eventConfig, $customerId, $secretKey);
    }

    public static function validateRequestByIntegrationConfig(
        $currentUrl,
        $queueitToken,
        $integrationsConfigString,
        $customerId,
        $secretKey
    ) {
        if (empty($currentUrl)) {
            throw new KnownUserException("currentUrl can not be null or empty.");
        }

        if (empty($integrationsConfigString)) {
            throw new KnownUserException("integrationsConfigString can not be null or empty.");
        }

        $eventConfig = new EventConfig();
        $targetUrl = "";

        try {
            $integrationEvaluator = new IntegrationEvaluator();
            $customerIntegration = json_decode($integrationsConfigString, true);
            $integrationConfig = $integrationEvaluator->getMatchedIntegrationConfig(
                $customerIntegration,
                $currentUrl,
                KnownUser::getCookieArray()
            );

            if ($integrationConfig == null) {
                return new RequestValidationResult(null, null, null);
            }

            $intergationKeys = ["EventId", "QueueDomain", "LayoutName", "Culture", "CookieDomain",
                "ExtendCookieValidity", "CookieValidityMinute", "Version"];

            foreach ($intergationKeys as $key) {
                $eventConfig->{lcfirst($key)} = isset($integrationConfig[$key]) ? $integrationConfig[$key] : null;
            }

            $integrationPolicy = isset($integrationConfig["RedirectLogic"]) ?
                $integrationConfig["RedirectLogic"] : null;

            switch ($integrationPolicy) {
                case "ForcedTargetUrl":
                case "ForecedTargetUrl":
                    $targetUrl = isset($integrationConfig["ForcedTargetUrl"]) ?
                        $integrationConfig["ForcedTargetUrl"] : '';
                    break;
                case "EventTargetUrl":
                    $targetUrl = "";
                    break;
                default:
                    $targetUrl = $currentUrl;
            }
        } catch (\Exception $e) {
            throw new KnownUserException("integrationConfiguration text was not valid: " . $e->getMessage());
        }

        return KnownUser::validateRequestByLocalEventConfig(
            $targetUrl,
            $queueitToken,
            $eventConfig,
            $customerId,
            $secretKey
        );
    }

    private static function getCookieArray()
    {
        $arryCookie = [];
        foreach ($_COOKIE as $key => $val) {
            $arryCookie[$key] = $val;
        }

        return $arryCookie;
    }
}
