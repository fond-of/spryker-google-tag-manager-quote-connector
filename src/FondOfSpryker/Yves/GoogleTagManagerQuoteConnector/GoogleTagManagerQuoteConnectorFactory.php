<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector;

use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToLocaleClientInterface;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander\DataLayerExpander;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander\DataLayerExpanderInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class GoogleTagManagerQuoteConnectorFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander\DataLayerExpanderInterface
     */
    public function createDataLayerExpander(): DataLayerExpanderInterface
    {
        return new DataLayerExpander(
            $this->getMoneyPlugin(),
            $this->getCartClient(),
            $this->getLocaleClient()
        );
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

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToLocaleClientInterface
     */
    public function getLocaleClient(): GoogleTagManagerQuoteConnectorToLocaleClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerQuoteConnectorDependencyProvider::LOCALE_CLIENT);
    }
}
