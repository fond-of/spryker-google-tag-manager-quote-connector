<?php

namespace FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Provider;

use FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Dependency\GoogleTagManageQuoteConnectorToCartClientInterface;

class GoogleTagManageQuoteProvider implements GoogleTagManageQuoteProviderInterface
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Dependency\GoogleTagManageQuoteConnectorToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManagerExtension\Dependency\GoogleTagManagerVariableBuilderPluginInterface[]
     */
    protected $googleTagManagerQuotePlugins;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Dependency\GoogleTagManageQuoteConnectorToCartClientInterface $cartClient
     * @param \FondOfSpryker\Yves\GoogleTagManagerExtension\Dependency\GoogleTagManagerVariableBuilderPluginInterface[] $googleTagManagerQuotePlugins
     */
    public function __construct(
        GoogleTagManageQuoteConnectorToCartClientInterface $cartClient,
        array $googleTagManagerQuotePlugins
    ) {
        $this->cartClient = $cartClient;
        $this->googleTagManagerQuotePlugins = $googleTagManagerQuotePlugins;
    }

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function addVariable(string $page, array $params): array
    {
        $quoteTransfer = $this->cartClient->getQuote();
        $params = array_merge($params, $quoteTransfer->toArray());
        $result = [];

        foreach ($this->googleTagManagerQuotePlugins as $plugin) {
            $result[] = $plugin->addVariable($page, $params);
        }

        return array_merge([], ...$result);
    }
}
