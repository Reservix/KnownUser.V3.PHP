<?php

namespace Reservix\Core\QueueBundle\Service;

use Reservix\Core\QueueBundle\Helper\CookieValidatorHelper;
use Reservix\Core\QueueBundle\Helper\IntegrationEvaluatorInterface;
use Reservix\Core\QueueBundle\Helper\UrlValidatorHelper;

class IntegrationEvaluator implements IntegrationEvaluatorInterface
{
    public function getMatchedIntegrationConfig(array $customerIntegration, $currentPageUrl, array $cookieList)
    {
        if (!array_key_exists("Integrations", $customerIntegration) || !is_array(
                $customerIntegration["Integrations"]
            )
        ) {
            return null;
        }
        foreach ($customerIntegration["Integrations"] as $integrationConfig) {
            if (!is_array($integrationConfig) || !array_key_exists("Triggers", $integrationConfig) || !is_array(
                    $integrationConfig["Triggers"]
                )
            ) {
                continue;
            }

            foreach ($integrationConfig["Triggers"] as $trigger) {
                if (!is_array($trigger)) {
                    return false;
                }
                if ($this->evaluateTrigger($trigger, $currentPageUrl, $cookieList)) {
                    return $integrationConfig;
                }
            }
        }

        return null;
    }

    private function evaluateTrigger(array $trigger, $currentPageUrl, array $cookieList)
    {
        if (!array_key_exists("LogicalOperator", $trigger) || !array_key_exists("TriggerParts", $trigger) || !is_array(
                $trigger["TriggerParts"]
            )
        ) {
            return false;
        }
        if ($trigger["LogicalOperator"] === "Or") {
            foreach ($trigger["TriggerParts"] as $triggerPart) {
                if (!is_array($triggerPart)) {
                    return false;
                }
                if ($this->evaluateTriggerPart($triggerPart, $currentPageUrl, $cookieList)) {
                    return true;
                }
            }

            return false;
        } else {
            foreach ($trigger["TriggerParts"] as $triggerPart) {
                if (!is_array($triggerPart)) {
                    return false;
                }
                if (!$this->evaluateTriggerPart($triggerPart, $currentPageUrl, $cookieList)) {
                    return false;
                }
            }

            return true;
        }
    }

    private function evaluateTriggerPart(array $triggerPart, $currentPageUrl, array $cookieList)
    {
        if (!array_key_exists("ValidatorType", $triggerPart)) {
            return false;
        }

        switch ($triggerPart["ValidatorType"]) {
            case "UrlValidator":
                return UrlValidatorHelper::evaluate($triggerPart, $currentPageUrl);
            case "CookieValidator":
                return CookieValidatorHelper::evaluate($triggerPart, $cookieList);
            default:
                return false;
        }
    }
}
