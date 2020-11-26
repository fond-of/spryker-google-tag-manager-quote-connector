<?php

namespace FondOfSpryker\Yves\GoogleTagManageQuoteConnector;

use FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Dependency\GoogleTagManageQuoteConnectorToCartClientBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Money\Plugin\MoneyPlugin;

class GoogleTagManageQuoteConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CART_CLIENT = 'CART_CLIENT';
    public const GOOGLE_TAG_MANAGER_QUOTE_PLUGINS = 'GOOGLE_TAG_MANAGER_QUOTE_PLUGINS';
    public const MONEY_PLUGIN = 'MONEY_PLUGIN';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCartClient($container);
        $container = $this->addGoogleTagManagerQuotePlugins($container);
        $container = $this->addMoneyPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container->set(static::CART_CLIENT, static function (Container $container) {
            return new GoogleTagManageQuoteConnectorToCartClientBridge(
                $container->getLocator()->cart()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGoogleTagManagerQuotePlugins(Container $container): Container
    {
        $container->set(static::GOOGLE_TAG_MANAGER_QUOTE_PLUGINS, function () {
            return $this->getGoogleTagManagerQuotePlugins();
        });

        return $container;
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManagerExtension\Dependency\GoogleTagManagerVariableBuilderPluginInterface[]
     */
    protected function getGoogleTagManagerQuotePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container): Container
    {
        $container->set(static::MONEY_PLUGIN, static function () {
            return new MoneyPlugin();
        });

        return $container;
    }
}
