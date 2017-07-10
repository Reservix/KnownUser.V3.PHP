<?php

namespace KnownUser\Helper;

interface IntegrationEvaluatorInterface
{
    public function getMatchedIntegrationConfig(array $customerIntegration, $currentPageUrl, array $cookieList);
}
