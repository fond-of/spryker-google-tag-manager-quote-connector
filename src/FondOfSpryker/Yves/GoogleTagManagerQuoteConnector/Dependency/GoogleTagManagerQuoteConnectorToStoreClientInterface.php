<?php


namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency;


use Generated\Shared\Transfer\StoreTransfer;

interface GoogleTagManagerQuoteConnectorToStoreClientInterface
{
    /**
     * @return StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer;
}
