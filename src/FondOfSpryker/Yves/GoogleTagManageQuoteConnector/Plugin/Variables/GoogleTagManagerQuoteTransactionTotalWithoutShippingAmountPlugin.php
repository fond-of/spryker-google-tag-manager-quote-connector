<?php

namespace FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Plugin\Variables;

use FondOfSpryker\Yves\GoogleTagManagerExtension\Dependency\GoogleTagManagerVariableBuilderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManageQuoteConnector\GoogleTagManageQuoteConnectorFactory getFactory()
 */
class GoogleTagManagerQuoteTransactionTotalWithoutShippingAmountPlugin extends AbstractPlugin implements GoogleTagManagerVariableBuilderPluginInterface
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
            ->createGoogleTagManageQuoteConnectorModel()
            ->getTransactionTotalWithoutShippingAmount($page, $params);
    }
}
