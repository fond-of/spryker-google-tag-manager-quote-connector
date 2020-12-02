<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector;

use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander\DataLayerExpander;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander\DataLayerExpanderInterface;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Model\GoogleTagManagerQuoteConnectorModel;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Model\GoogleTagManagerQuoteConnectorModelInterface;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Provider\GoogleTagManagerQuoteProvider;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Provider\GoogleTagManagerQuoteProviderInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class GoogleTagManagerQuoteConnectorFactory extends AbstractFactory
{
    /**
     * @return DataLayerExpanderInterface
     */
    public function createDataLayerExpander(): DataLayerExpanderInterface
    {
        return new DataLayerExpander($this->getMoneyPlugin(), $this->getCartClient());
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface
     */
    public function getCartClient(): GoogleTagManagerQuoteConnectorToCartClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerQuoteConnectorDependencyProvider::CART_CLIENT);
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    public function getMoneyPlugin(): MoneyPluginInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerQuoteConnectorDependencyProvider::MONEY_PLUGIN);
    }
}
