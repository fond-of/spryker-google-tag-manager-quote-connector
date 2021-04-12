<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToLocaleClientInterface;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class DataLayerExpanderTest extends Unit
{
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
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ItemTransfer
     */
    protected $itemTransferMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander\DataLayerExpanderInterface
     */
    protected $expander;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->cartClientMock = $this->getMockBuilder(GoogleTagManagerQuoteConnectorToCartClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->localeClientMock = $this->getMockBuilder(GoogleTagManagerQuoteConnectorToLocaleClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->moneyPluginMock = $this->getMockBuilder(MoneyPluginInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeTransferMock = $this->getMockBuilder(StoreTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->itemTransferMock = $this->getMockBuilder(ItemTransfer::class)
            ->setMethods(['getAbstractAttributes', 'getSku'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->expander = new DataLayerExpander(
            $this->moneyPluginMock,
            $this->cartClientMock,
            $this->localeClientMock
        );
    }

    /**
     * @return void
     */
    public function testExpand(): void
    {
        $quoteTransfer = (new QuoteTransfer())
            ->fromArray(include codecept_data_dir('quote_transfer_array.php'), true);

        $this->moneyPluginMock->expects($this->atLeastOnce())
            ->method('convertIntegerToDecimal')
            ->willReturn(39.90);

        $this->localeClientMock->expects($this->atLeastOnce())
            ->method('getCurrentLocale')
            ->willReturn('de_DE');

        $result = $this->expander->expand('pageType', [
            ModuleConstants::PARAMETER_QUOTE => $quoteTransfer,
        ], []);

        $this->assertIsArray($result);
        $this->assertArrayHasKey(ModuleConstants::FIELD_TRANSACTION_ENTITY, $result);
        $this->assertArrayHasKey(ModuleConstants::FIELD_CUSTOMER_EMAIL, $result);
        $this->assertArrayHasKey(ModuleConstants::FIELD_EXTERNAL_ID_HASH, $result);
        $this->assertArrayHasKey(ModuleConstants::FIELD_TRANSACTION_AFFILIATION, $result);
        $this->assertArrayHasKey(ModuleConstants::FIELD_TRANSACTION_PRODUCTS_SKUS, $result);
        $this->assertArrayHasKey(ModuleConstants::FIELD_TRANSACTION_TOTAL, $result);
        $this->assertArrayHasKey(ModuleConstants::FIELD_TRANSACTION_WITHOUT_SHIPPING_AMOUNT, $result);
        $this->assertArrayHasKey(ModuleConstants::FIELD_TRANSACTION_PRODUCTS, $result);
    }
}
