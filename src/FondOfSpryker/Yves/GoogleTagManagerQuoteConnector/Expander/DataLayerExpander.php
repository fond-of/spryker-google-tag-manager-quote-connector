<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander;

use FondOfSpryker\Shared\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class DataLayerExpander implements DataLayerExpanderInterface
{
    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @var GoogleTagManagerQuoteConnectorToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface $cartClient
     */
    public function __construct(
        MoneyPluginInterface $moneyPlugin,
        GoogleTagManagerQuoteConnectorToCartClientInterface $cartClient
    )
    {
        $this->moneyPlugin = $moneyPlugin;
        $this->cartClient = $cartClient;
    }

    /**
     * @param string $page
     * @param array $twigVariableBag
     * @param array $dataLayer
     *
     * @return array
     */
    public function expand(string $page, array $twigVariableBag, array $dataLayer): array
    {
        $quoteTransfer = $twigVariableBag[ModuleConstants::PARAMETER_QUOTE];

        $dataLayer[ModuleConstants::FIELD_TRANSACTION_ENTITY] = 'QUOTE';
        $dataLayer[ModuleConstants::FIELD_CUSTOMER_EMAIL] = $this->getCustomerEmail($quoteTransfer);
        $dataLayer[ModuleConstants::FIELD_EXTERNAL_ID_HASH] = $this->getCustomerEmail($quoteTransfer)
            ? sha1($this->getCustomerEmail($quoteTransfer))
            : null;
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_AFFILIATION] = $quoteTransfer->getStore()->getName();
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_PRODUCTS_SKUS] = $this->getProductSkus($quoteTransfer);
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_TAX] = $this->getTax($quoteTransfer);
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_TOTAL] = $this->getTotal($quoteTransfer);
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_WITHOUT_SHIPPING_AMOUNT] = $this->getTotalWithoutShippingAmount($quoteTransfer);

        return $this->deleteEmptyIndexFromDataLayer($dataLayer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return null|string
     */
    protected function getCustomerEmail(QuoteTransfer $quoteTransfer): ?string
    {
        if ($quoteTransfer->getBillingAddress() === null) {
            return null;
        }

        if ($quoteTransfer->getBillingAddress()->getEmail() === null) {
            return null;
        }

        return $quoteTransfer->getBillingAddress()->getEmail();
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getProductSkus(QuoteTransfer $quoteTransfer): array
    {
        $skuCollection = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $skuCollection[] = $itemTransfer->getSku();
        }

        return $skuCollection;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function getTax(QuoteTransfer $quoteTransfer): ?float
    {
        if ($quoteTransfer->getTotals() === null) {
            return null;
        }

        if ($quoteTransfer->getTotals()->getTaxTotal() === null) {
            return null;
        }

        return (float) $quoteTransfer->getTotals()->getTaxTotal()->getAmount();
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function getTotal(QuoteTransfer $quoteTransfer): ?float
    {
        if ($quoteTransfer->getTotals() === null) {
            return null;
        }

        return $this->moneyPlugin->convertIntegerToDecimal(
            (int) $quoteTransfer->getTotals()->getGrandTotal()
        );
    }

    /**
     * @return float|null
     */
    protected function getTotalWithoutShippingAmount(QuoteTransfer $quoteTransfer): ?float
    {
        $shipmentTotal = $quoteTransfer->getTotals() instanceof TotalsTransfer
            ? (int)$quoteTransfer->getTotals()->getShipmentTotal() : null;

        $grandTotal = $quoteTransfer->getTotals() instanceof TotalsTransfer
            ? (int)$quoteTransfer->getTotals()->getGrandTotal() : null;

        if ($shipmentTotal === null || $grandTotal === null) {
            return null;
        }

        return $this->moneyPlugin->convertIntegerToDecimal($grandTotal - $shipmentTotal);
    }

    /**
     * @param array $dataLayer
     *
     * @return array
     */
    protected function deleteEmptyIndexFromDataLayer(array $dataLayer): array
    {
        foreach ($dataLayer as $key => $item) {
            if ($item === null) {
                unset($dataLayer[$key]);
            }

            if ($item === '') {
                unset($dataLayer[$key]);
            }

            if (is_array($item) && count($item) === 0) {
                unset($dataLayer[$key]);
            }
        }

        return $dataLayer;
    }
}
