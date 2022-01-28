<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Expander;

use FondOfSpryker\Shared\GoogleTagManagerQuoteConnector\GoogleTagManagerQuoteConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToLocaleClientInterface;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class DataLayerExpander implements DataLayerExpanderInterface
{
    public const UNTRANSLATED_KEY = '_';
    public const EN_KEY = 'en_US';

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency\GoogleTagManagerQuoteConnectorToCartClientInterface $cartClient
     */
    public function __construct(
        MoneyPluginInterface $moneyPlugin,
        GoogleTagManagerQuoteConnectorToCartClientInterface $cartClient,
        GoogleTagManagerQuoteConnectorToLocaleClientInterface $localeClient
    ) {
        $this->moneyPlugin = $moneyPlugin;
        $this->cartClient = $cartClient;
        $this->localeClient = $localeClient;
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
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_TOTAL] = $this->getTotal($quoteTransfer);
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_WITHOUT_SHIPPING_AMOUNT] = $this->getTotalWithoutShippingAmount($quoteTransfer);
        $dataLayer[ModuleConstants::FIELD_TRANSACTION_PRODUCTS] = $this->getProducts($quoteTransfer);

        return $this->deleteEmptyIndexFromDataLayer($dataLayer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getProducts(QuoteTransfer $quoteTransfer): array
    {
        $products = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $product = [
                ModuleConstants::FIELD_PRODUCT_ID => $itemTransfer->getId(),
                ModuleConstants::FIELD_PRODUCT_NAME => $this->getProductName($itemTransfer),
                ModuleConstants::FIELD_PRODUCT_SKU => $itemTransfer->getSku(),
                ModuleConstants::FIELD_PRODUCT_PRICE => $this->getProductPrice($itemTransfer),
                ModuleConstants::FIELD_PRODUCT_QUANTITY => $itemTransfer->getQuantity(),
                ModuleConstants::FIELD_PRODUCT_EAN => $this->getProductEan($itemTransfer),
                ModuleConstants::FIELD_PRODUCT_BRAND => $this->getProductBrand($itemTransfer),
                ModuleConstants::FIELD_PRODUCT_IMAGE_URL => $this->getProductImage($itemTransfer),
                ModuleConstants::FIELD_PRODUCT_URL => $this->getProductUrl($itemTransfer),
            ];

            $products[] = $product;
        }

        return $products;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return float|null
     */
    protected function getTotal(QuoteTransfer $quoteTransfer): ?float
    {
        if ($quoteTransfer->getTotals() === null) {
            return null;
        }

        return $this->moneyPlugin->convertIntegerToDecimal(
            (int)$quoteTransfer->getTotals()->getGrandTotal()
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

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return float
     */
    protected function getProductPrice(ItemTransfer $itemTransfer): float
    {
        return $this->moneyPlugin->convertIntegerToDecimal($itemTransfer->getUnitPrice());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getProductName(ItemTransfer $itemTransfer): string
    {
        $productAttributes = $itemTransfer->getAbstractAttributes();

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_MODEL_UNTRANSLATED])
            && !empty($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_MODEL_UNTRANSLATED]))
        {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_MODEL_UNTRANSLATED];
        }

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_MODEL])
            && !empty($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_MODEL]))
        {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_MODEL];
        }

        return $itemTransfer->getName();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getProductEan(ItemTransfer $itemTransfer): string
    {
        $productAttributes = $itemTransfer->getAbstractAttributes();

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_EAN])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_EAN];
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getProductBrand(ItemTransfer $itemTransfer): string
    {
        $productAttributes = $itemTransfer->getAbstractAttributes();

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_BRAND])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAMETER_PRODUCT_ATTR_BRAND];
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getProductImage(ItemTransfer $itemTransfer): string
    {
        foreach ($itemTransfer->getImages() as $imageTransfer) {
            return $imageTransfer->getExternalUrlSmall();
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getProductUrl(ItemTransfer $itemTransfer): string
    {
        $currentLocale = $this->localeClient->getCurrentLocale();
        $productAttributes = $itemTransfer->getAbstractAttributes();

        if (isset($productAttributes[$currentLocale][ModuleConstants::PARAMETER_PRODUCT_ATTR_URL])) {
            return $productAttributes[$currentLocale][ModuleConstants::PARAMETER_PRODUCT_ATTR_URL];
        }

        return $itemTransfer->getUrl();
    }
}
