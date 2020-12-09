<?php


namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency;


interface GoogleTagManagerQuoteConnectorToLocaleClientInterface
{
    /**
     * @return string
     */
    public function getCurrentLocale(): string;
}
