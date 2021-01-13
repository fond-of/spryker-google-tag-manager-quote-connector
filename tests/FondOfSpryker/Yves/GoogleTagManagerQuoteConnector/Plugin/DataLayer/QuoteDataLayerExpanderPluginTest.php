<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Plugin\DataLayer;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorConstants;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander\DataLayerExpanderInterface;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorFactory;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteDataLayerExpanderPluginTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorFactory
     */
    protected $factoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander\DataLayerExpanderInterface
     */
    protected $expanderMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManagerExtension\Dependency\GoogleTagManagerDataLayerExpanderPluginInterface
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

        $this->expanderMock = $this->getMockBuilder(DataLayerExpanderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = new QuoteDataLayerExpanderPlugin();
        $this->plugin->setFactory($this->factoryMock);
    }

    /**
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->assertEquals(true, $this->plugin->isApplicable('pageType', [
            GoogleTagManagerQuoteConnectorConstants::PARAMETER_QUOTE => new QuoteTransfer(),
        ]));
    }

    /**
     * @return void
     */
    public function testExpand(): void
    {
        $this->factoryMock->expects($this->atLeastOnce())
            ->method('createDataLayerExpander')
            ->willReturn($this->expanderMock);

        $this->expanderMock->expects($this->atLeastOnce())
            ->method('expand')
            ->willReturn([]);

        $this->plugin->expand('pageType', [], []);
    }
}
