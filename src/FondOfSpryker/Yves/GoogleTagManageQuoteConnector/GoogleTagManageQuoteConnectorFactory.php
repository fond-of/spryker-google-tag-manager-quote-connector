<?php

namespace FondOfSpryker\Yves\GoogleTagManageQuoteConnector;

use FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Dependency\GoogleTagManageQuoteConnectorToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Model\GoogleTagManageQuoteConnectorModel;
use FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Model\GoogleTagManageQuoteConnectorModelInterface;
use FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Provider\GoogleTagManageQuoteProvider;
use FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Provider\GoogleTagManageQuoteProviderInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class GoogleTagManageQuoteConnectorFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Model\GoogleTagManageQuoteConnectorModelInterface
     */
    public function createGoogleTagManageQuoteConnectorModel(): GoogleTagManageQuoteConnectorModelInterface
    {
        return new GoogleTagManageQuoteConnectorModel(
            $this->getMoneyPlugin()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Provider\GoogleTagManageQuoteProviderInterface
     */
    public function createGoogleTagManageQuoteConnectorProvider(): GoogleTagManageQuoteProviderInterface
    {
        return new GoogleTagManageQuoteProvider(
            $this->getCartClient(),
            $this->getGoogleTagManagerQuotePlugins()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Dependency\GoogleTagManageQuoteConnectorToCartClientInterface
     */
    public function getCartClient(): GoogleTagManageQuoteConnectorToCartClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManageQuoteConnectorDependencyProvider::CART_CLIENT);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManagerExtension\Dependency\GoogleTagManagerVariableBuilderPluginInterface[]
     */
    public function getGoogleTagManagerQuotePlugins(): array
    {
        return $this->getProvidedDependency(GoogleTagManageQuoteConnectorDependencyProvider::GOOGLE_TAG_MANAGER_QUOTE_PLUGINS);
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    public function getMoneyPlugin(): MoneyPluginInterface
    {
        return $this->getProvidedDependency(GoogleTagManageQuoteConnectorDependencyProvider::MONEY_PLUGIN);
    }
}
