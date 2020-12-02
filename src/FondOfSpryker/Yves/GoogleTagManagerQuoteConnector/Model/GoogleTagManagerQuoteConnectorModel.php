<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Model;

use FondOfSpryker\Shared\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorConstants;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class GoogleTagManagerQuoteConnectorModel implements GoogleTagManagerQuoteConnectorModelInterface
{
    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface $cartClient
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
            GoogleTagManagerQuoteConnectorConstants::FIELD_TRANSACTION_AFFILIATION => $quoteTransfer->getStore()->getName(),
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
            GoogleTagManagerQuoteConnectorConstants::FIELD_TRANSACTION_TOTAL => $this->moneyPlugin
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

        $shipmentTotal = $quoteTransfer->getTotals() instanceof TotalsTransfer
            ? (int)$quoteTransfer->getTotals()->getShipmentTotal() : 0;

        $grandTotal = $quoteTransfer->getTotals() instanceof TotalsTransfer
            ? (int)$quoteTransfer->getTotals()->getGrandTotal() : 0;

        $totalWithoutShippingAmount = $this->moneyPlugin->convertIntegerToDecimal($grandTotal - $shipmentTotal);

        return [
            GoogleTagManagerQuoteConnectorConstants::FIELD_TRANSACTION_WITHOUT_SHIPPING_AMOUNT => $totalWithoutShippingAmount,
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
            GoogleTagManagerQuoteConnectorConstants::FIELD_TRANSACTION_TAX => $this->moneyPlugin->convertIntegerToDecimal($tax),
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
            GoogleTagManagerQuoteConnectorConstants::FIELD_TRANSACTION_PRODUCTS_SKUS => $skuCollection,
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

        if ($quoteTransfer->getBillingAddress() === null) {
            return [];
        }

        if ($quoteTransfer->getBillingAddress()->getEmail() === null) {
            return [];
        }

        return [
            GoogleTagManagerQuoteConnectorConstants::FIELD_CUSTOMER_EMAIL => $quoteTransfer->getBillingAddress()->getEmail(),
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
            GoogleTagManagerQuoteConnectorConstants::FIELD_TRANSACTION_ENTITY => 'QUOTE',
        ];
    }

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getEmailHash(string $page, array $params): array
    {
        $quoteTransfer = $this->createQuoteTransferFormArray($params);

        if ($quoteTransfer->getBillingAddress() === null) {
            return [];
        }

        if ($quoteTransfer->getBillingAddress()->getEmail() === null) {
            return [];
        }

        return [
            GoogleTagManagerQuoteConnectorConstants::FIELD_EXTERNAL_ID_HASH => sha1($quoteTransfer->getBillingAddress()->getEmail()),
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
