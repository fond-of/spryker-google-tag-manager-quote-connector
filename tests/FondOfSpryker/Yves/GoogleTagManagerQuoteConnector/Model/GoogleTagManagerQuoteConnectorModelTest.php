<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Model;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorConstants;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class GoogleTagManagerQuoteConnectorModelTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPluginMock;

    /**
     * @var GoogleTagManagerQuoteConnectorModelInterface
     */
    protected $model;

    /**
     * @var array
     */
    protected $params;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->params = include codecept_data_dir('quote_transfer_array.php');

        $this->moneyPluginMock = $this->getMockBuilder(MoneyPluginInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->model = new GoogleTagManagerQuoteConnectorModel($this->moneyPluginMock);
    }

    /**
     * @return void
     */
    public function testGetTransactionTotal(): void
    {
        $this->moneyPluginMock->expects($this->once())
            ->method('convertIntegerToDecimal')
            ->with(4980);

        $result = $this->model->getTransactionTotal('pageType', $this->params);

        $this->assertIsArray($result);
        $this->assertArrayHasKey(GoogleTagManagerQuoteConnectorConstants::FIELD_TRANSACTION_TOTAL, $result);
    }

    /**
     * @return void
     */
    public function testGetTransactionTotalWithoutShippingAmount(): void
    {
        $result = $this->model->getTransactionTotalWithoutShippingAmount('pageType', $this->params);

        $this->assertIsArray($result);
        $this->assertArrayHasKey(
            GoogleTagManagerQuoteConnectorConstants::FIELD_TRANSACTION_WITHOUT_SHIPPING_AMOUNT,
            $result
        );
    }
}
