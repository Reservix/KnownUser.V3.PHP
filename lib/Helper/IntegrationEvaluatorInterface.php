<?php

namespace Reservix\Core\QueueBundle\Helper;

interface IntegrationEvaluatorInterface
{
    public function getMatchedIntegrationConfig(array $customerIntegration, $currentPageUrl, array $cookieList);
}
