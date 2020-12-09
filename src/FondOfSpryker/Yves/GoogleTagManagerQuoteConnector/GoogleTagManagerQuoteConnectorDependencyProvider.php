<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector;

use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientBridge;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToLocaleClientBridge;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToStoreClientBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Money\Plugin\MoneyPlugin;

class GoogleTagManagerQuoteConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CART_CLIENT = 'CART_CLIENT';
    public const STORE_CLIENT = 'STORE_CLIENT';
    public const LOCALE_CLIENT = 'LOCALE_CLIENT';
    public const MONEY_PLUGIN = 'MONEY_PLUGIN';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCartClient($container);
        $container = $this->addMoneyPlugin($container);
        $container = $this->addStoreClient($container);
        $container = $this->addLocaleClient($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::STORE_CLIENT, static function(Container $container) {
            return new GoogleTagManagerQuoteConnectorToStoreClientBridge(
                $container->getLocator()->store()->client()
            );
        });

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
            return new GoogleTagManagerQuoteConnectorToCartClientBridge(
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
    protected function addMoneyPlugin(Container $container): Container
    {
        $container->set(static::MONEY_PLUGIN, static function () {
            return new MoneyPlugin();
        });

        return $container;
    }

    /**
     * @param Container $container
     * @return Container
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::LOCALE_CLIENT, static function (Container $container) {
            return new GoogleTagManagerQuoteConnectorToLocaleClientBridge(
                $container->getLocator()->locale()->client()
            );
        });

        return $container;
    }
}
