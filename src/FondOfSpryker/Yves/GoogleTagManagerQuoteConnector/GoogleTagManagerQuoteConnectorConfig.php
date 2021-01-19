<?php


namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector;

use FondOfSpryker\Shared\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class GoogleTagManagerQuoteConnectorConfig extends AbstractBundleConfig
{
    public function getProtocol(): string
    {
        return $this->get(GoogleTagManagerQuoteConnectorConstants::GTM_PROTOCOL, 'http');
    }
}
