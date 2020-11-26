<?php

namespace FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Model;

use FondOfSpryker\Shared\GoogleTagManageQuoteConnector\GoogleTagManageQuoteConnectorConstants;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class GoogleTagManageQuoteConnectorModel implements GoogleTagManageQuoteConnectorModelInterface
{
    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Dependency\GoogleTagManageQuoteConnectorToCartClientInterface $cartClient
     */
    public function __construct(MoneyPluginInterface $moneyPlugin)
    {
        $this->moneyPlugin = $moneyPlugin;
    }

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getTransactionAffiliation(string $page, array $params): array
    {
        $quoteTransfer = $this->createQuoteTransferFormArray($params);

        return [
            GoogleTagManageQuoteConnectorConstants::FIELD_TRANSACTION_AFFILIATION => $quoteTransfer->getStore()->getName(),
        ];
    }

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getTransactionTotal(string $page, array $params): array
    {
        return [
            GoogleTagManageQuoteConnectorConstants::FIELD_TRANSACTION_TOTAL => $this->moneyPlugin
                ->convertIntegerToDecimal(
                    $this->createQuoteTransferFormArray($params)
                        ->getTotals()
                        ->getGrandTotal()
                ),
        ];
    }

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getTransactionTotalWithoutShippingAmount(string $page, array $params): array
    {
        $quoteTransfer = $this->createQuoteTransferFormArray($params);

        $shipmentTotal = (int) $quoteTransfer->getTotals()->getShipmentTotal();
        $grandTotal = (int) $quoteTransfer->getTotals()->getGrandTotal();
        $totalWithoutShippingAmount = $this->moneyPlugin->convertIntegerToDecimal($grandTotal - $shipmentTotal);

        return [
            GoogleTagManageQuoteConnectorConstants::FIELD_TRANSACTION_WITHOUT_SHIPPING_AMOUNT => $totalWithoutShippingAmount
        ];
    }

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getTransactionTax(string $page, array $params): array
    {
        $quoteTransfer = $this->createQuoteTransferFormArray($params);
        $tax = $quoteTransfer->getTotals()->getTaxTotal()->getAmount();

        return [
            GoogleTagManageQuoteConnectorConstants::FIELD_TRANSACTION_TAX => $this->moneyPlugin->convertIntegerToDecimal($tax)
        ];
    }

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getTransactionProductsSkus(string $page, array $params): array
    {
        $quoteTransfer = $this->createQuoteTransferFormArray($params);
        $skuCollection = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $skuCollection[] = $itemTransfer->getSku();
        }

        return [
            GoogleTagManageQuoteConnectorConstants::FIELD_TRANSACTION_PRODUCTS_SKUS => $skuCollection
        ];
    }

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getCustomerEmail(string $page, array $params): array
    {
        $quoteTransfer = $this->createQuoteTransferFormArray($params);

        return [
            GoogleTagManageQuoteConnectorConstants::FIELD_CUSTOMER_EMAIL => $quoteTransfer->getBillingAddress()->getEmail()
        ];
    }

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getTransactionEntity(string $page, array $params): array
    {
        return [
            GoogleTagManageQuoteConnectorConstants::FIELD_TRANSACTION_ENTITY => 'QUOTE',
        ];
    }

    /**
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferFormArray(array $params): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->fromArray($params, true);
    }
}
