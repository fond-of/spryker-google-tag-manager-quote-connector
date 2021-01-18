<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Plugin\TwigParameterBagExpander;

use ArrayObject;
use Codeception\Test\Unit;
use FondOfSpryker\Shared\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorConstants;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorFactory;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteTwigParameterBagExpanderPluginTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorFactory
     */
    protected $factoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface
     */
    protected $cartClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ItemTransfer
     */
    protected $itemTransferMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManagerExtension\Dependency\TwigParameterBagExpanderPluginInterface
     */
    protected $plugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->factoryMock = $this->getMockBuilder(GoogleTagManagerQuoteConnectorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cartClientMock = $this->getMockBuilder(GoogleTagManagerQuoteConnectorToCartClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->itemTransferMock = $this->getMockBuilder(ItemTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = new QuoteTwigParameterBagExpanderPlugin();
        $this->plugin->setFactory($this->factoryMock);
    }

    /**
     * @return void
     */
    public function testIsApplicable(): void
    {
        $items = new ArrayObject();
        $items->append($this->itemTransferMock);
        $items->append($this->itemTransferMock);
        $items->append($this->itemTransferMock);

        $this->factoryMock->expects($this->atLeastOnce())
            ->method('getCartClient')
            ->willReturn($this->cartClientMock);

        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteTransferMock->expects($this->once())
            ->method('getItems')
            ->willReturn($items);

        $this->plugin->isApplicable('pageType', []);
    }

    /**
     * @return void
     */
    public function testExpand(): void
    {
        $this->factoryMock->expects($this->atLeastOnce())
            ->method('getCartClient')
            ->willReturn($this->cartClientMock);

        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->assertArrayHasKey(GoogleTagManagerQuoteConnectorConstants::PARAMETER_QUOTE, $this->plugin->expand([]));
    }
}
