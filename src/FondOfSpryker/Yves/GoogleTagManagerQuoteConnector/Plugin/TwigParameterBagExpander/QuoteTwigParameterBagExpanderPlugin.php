<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Plugin\TwigParameterBagExpander;

use FondOfSpryker\Shared\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorConstants;
use FondOfSpryker\Yves\GoogleTagManagerExtension\Dependency\TwigParameterBagExpanderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorFactory getFactory()
 */
class QuoteTwigParameterBagExpanderPlugin extends AbstractPlugin implements TwigParameterBagExpanderPluginInterface
{
    /**
     * @param string $pageType
     * @param array $twigVariableBag
     *
     * @return bool
     */
    public function isApplicable(string $pageType, array $twigVariableBag = []): bool
    {
        return $this->getFactory()->getCartClient()->getQuote()->getItems()->count() > 0;
    }

    /**
     * @param array $twigVariableBag
     *
     * @return array
     */
    public function expand($twigVariableBag): array
    {
        $twigVariableBag[GoogleTagManagerQuoteConnectorConstants::PARAMETER_QUOTE] = $this->getFactory()->getCartClient()->getQuote();

        return $twigVariableBag;
    }
}
