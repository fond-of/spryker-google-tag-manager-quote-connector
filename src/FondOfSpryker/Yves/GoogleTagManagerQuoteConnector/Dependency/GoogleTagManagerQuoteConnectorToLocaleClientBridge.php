<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency;

use Spryker\Client\Locale\LocaleClientInterface;

class GoogleTagManagerQuoteConnectorToLocaleClientBridge implements GoogleTagManagerQuoteConnectorToLocaleClientInterface
{
    /**
     * @var \Spryker\Client\Locale\LocaleClientInterface
     */
    protected $localeClient;

    public function __construct(LocaleClientInterface $localeClient)
    {
        $this->localeClient = $localeClient;
    }

    /**
     * @return string
     */
    public function getCurrentLocale(): string
    {
        return $this->localeClient->getCurrentLocale();
    }
}
