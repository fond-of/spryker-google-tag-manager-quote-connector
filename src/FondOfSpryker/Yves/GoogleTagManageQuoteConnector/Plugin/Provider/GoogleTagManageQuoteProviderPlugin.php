<?php

namespace FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Plugin\Provider;

use FondOfSpryker\Yves\GoogleTagManagerExtension\Dependency\GoogleTagManagerVariableBuilderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManageQuoteConnector\GoogleTagManageQuoteConnectorFactory getFactory()
 */
class GoogleTagManageQuoteProviderPlugin extends AbstractPlugin implements GoogleTagManagerVariableBuilderPluginInterface
{
    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function addVariable(string $page, array $params): array
    {
        return $this->getFactory()
            ->createGoogleTagManageQuoteConnectorProvider()
            ->addVariable($page, $params);
    }
}
