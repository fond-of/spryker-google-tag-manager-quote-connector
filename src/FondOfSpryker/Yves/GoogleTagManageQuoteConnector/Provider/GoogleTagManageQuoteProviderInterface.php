<?php

namespace FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Provider;

interface GoogleTagManageQuoteProviderInterface
{
    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function addVariable(string $page, array $params): array;
}
