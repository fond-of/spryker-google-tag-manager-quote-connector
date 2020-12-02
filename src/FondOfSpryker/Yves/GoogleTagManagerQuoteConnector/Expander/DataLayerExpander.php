<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander;

use Facebook\WebDriver\Exception\NullPointerException;
use FondOfSpryker\Shared\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class DataLayerExpander implements DataLayerExpanderInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @var GoogleTagManagerQuoteConnectorToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

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
        $this->quoteTransfer = $this->cartClient->getQuote();
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
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_ENTITY] = 'QUOTE';
        $dataLayer[ModuleConstants::FIELD_CUSTOMER_EMAIL] = $this->getCustomerEmail();
        $dataLayer[ModuleConstants::FIELD_EXTERNAL_ID_HASH] = sha1($this->getCustomerEmail());
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_AFFILIATION] = $this->quoteTransfer->getStore()->getName();
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_PRODUCTS_SKUS] = $this->getProductSkus();
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_TAX] = $this->getTax();
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_TOTAL] = $this->getTotal();
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_WITHOUT_SHIPPING_AMOUNT] = $this->getTotalWithoutShippingAmount();

        return $this->deleteEmptyIndexFromDataLayer($dataLayer);
    }

    /**
     * @return string
     */
    protected function getCustomerEmail(): string
    {
        if ($this->quoteTransfer->getBillingAddress() === null) {
            return '';
        }

        if ($this->quoteTransfer->getBillingAddress()->getEmail() === null) {
            return '';
        }

        return $this->quoteTransfer->getBillingAddress()->getEmail();
    }

    /**
     * @return array
     */
    protected function getProductSkus(): array
    {
        $skuCollection = [];

        foreach ($this->quoteTransfer->getItems() as $itemTransfer) {
            $skuCollection[] = $itemTransfer->getSku();
        }

        return $skuCollection;
    }

    /**
     * @return float
     */
    protected function getTax(): ?float
    {
        if ($this->quoteTransfer->getTotals() === null) {
            return null;
        }

        if ($this->quoteTransfer->getTotals()->getTaxTotal() === null) {
            return null;
        }

        return (float) $this->quoteTransfer->getTotals()->getTaxTotal()->getAmount();
    }

    /**
     * @return float
     */
    protected function getTotal(): ?float
    {
        if ($this->quoteTransfer->getTotals() === null) {
            return null;
        }

        return $this->moneyPlugin->convertIntegerToDecimal(
            (int) $this->quoteTransfer->getTotals()->getGrandTotal()
        );
    }

    /**
     * @return float|null
     */
    protected function getTotalWithoutShippingAmount(): ?float
    {
        $shipmentTotal = $this->quoteTransfer->getTotals() instanceof TotalsTransfer
            ? (int)$this->quoteTransfer->getTotals()->getShipmentTotal() : null;

        $grandTotal = $this->quoteTransfer->getTotals() instanceof TotalsTransfer
            ? (int)$this->quoteTransfer->getTotals()->getGrandTotal() : null;

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
