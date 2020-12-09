<?php


namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency;


use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Store\StoreClientInterface;

class GoogleTagManagerQuoteConnectorToStoreClientBridge implements GoogleTagManagerQuoteConnectorToStoreClientInterface
{
    /**
     * @var StoreClientInterface
     */
    protected $storeClient;

    /**
     * @param StoreClientInterface $storeClient
     */
    public function __construct(StoreClientInterface $storeClient)
    {
        $this->storeClient = $storeClient;
    }

    /**
     * @return StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer
    {
        return $this->storeClient->getCurrentStore();
    }
}
