<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToLocaleClientInterface;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander\DataLayerExpanderInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\Container;

class GoogleTagManagerQuoteConnectorFactoryTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\Container
     */
    protected $containerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface
     */
    protected $cartClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToLocaleClientInterface
     */
    protected $localeClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPluginMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|GoogleTagManagerQuoteConnectorConfig
     */
    protected $configMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorFactory
     */
    protected $factory;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cartClientMock = $this->getMockBuilder(GoogleTagManagerQuoteConnectorToCartClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->moneyPluginMock = $this->getMockBuilder(MoneyPluginInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->localeClientMock = $this->getMockBuilder(GoogleTagManagerQuoteConnectorToLocaleClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(GoogleTagManagerQuoteConnectorConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory = new GoogleTagManagerQuoteConnectorFactory();
        $this->factory->setContainer($this->containerMock);
        $this->factory->setConfig($this->configMock);
    }

    /**
     * @return void
     */
    public function testCreateDataLayerExpander(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->moneyPluginMock, $this->cartClientMock, $this->localeClientMock, $this->configMock);

        $this->assertInstanceOf(
            DataLayerExpanderInterface::class,
            $this->factory->createDataLayerExpander()
        );
    }

    /**
     * @return void
     */
    public function testGetCartClient(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->localeClientMock);

        $this->assertInstanceOf(
            GoogleTagManagerQuoteConnectorToLocaleClientInterface::class,
            $this->factory->getLocaleClient()
        );
    }

    /**
     * @return void
     */
    public function testGetMoneyPlugin(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->moneyPluginMock);

        $this->assertInstanceOf(
            MoneyPluginInterface::class,
            $this->factory->getMoneyPlugin()
        );
    }

    /**
     * @return void
     */
    public function testGetLocaleClient(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->moneyPluginMock);

        $this->assertInstanceOf(
            MoneyPluginInterface::class,
            $this->factory->getMoneyPlugin()
        );
    }
}
