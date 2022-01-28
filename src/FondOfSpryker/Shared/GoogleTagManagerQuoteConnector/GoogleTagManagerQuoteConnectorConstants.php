<?php

namespace FondOfSpryker\Shared\GoogleTagManagerQuoteConnector;

interface GoogleTagManagerQuoteConnectorConstants
{
    public const PAGE_TYPE_PRODUCT = 'product';

    public const FIELD_TRANSACTION_AFFILIATION = 'transactionAffiliation';
    public const FIELD_TRANSACTION_TOTAL = 'transactionTotal';
    public const FIELD_TRANSACTION_WITHOUT_SHIPPING_AMOUNT = 'transactionTotalWithoutShippingAmount';
    public const FIELD_TRANSACTION_TAX = 'transactionTax';
    public const FIELD_TRANSACTION_PRODUCTS_SKUS = 'transactionProductsSkus';
    public const FIELD_TRANSACTION_ENTITY = 'transactionEntity';
    public const FIELD_CUSTOMER_EMAIL = 'customerEmail';
    public const FIELD_EXTERNAL_ID_HASH = 'externalIdHash';
    public const FIELD_TRANSACTION_PRODUCTS = 'transactionProducts';

    public const FIELD_PRODUCT_ID = 'id';
    public const FIELD_PRODUCT_SKU = 'sku';
    public const FIELD_PRODUCT_PRICE = 'price';
    public const FIELD_PRODUCT_QUANTITY = 'quantity';
    public const FIELD_PRODUCT_NAME = 'name';
    public const FIELD_PRODUCT_EAN = 'ean';
    public const FIELD_PRODUCT_BRAND = 'brand';
    public const FIELD_PRODUCT_IMAGE_URL = 'imageUrl';
    public const FIELD_PRODUCT_URL = 'url';

    public const PARAMETER_QUOTE = 'PARAMETER_QUOTE';
    public const PARAMETER_PRODUCT_ATTR_NAME_UNTRANSLATED = 'name_untranslated';
    public const PARAMETER_PRODUCT_ATTR_MODEL_UNTRANSLATED = 'model_untranslated';
    public const PARAMETER_PRODUCT_ATTR_MODEL = 'model';
    public const PARAMETER_PRODUCT_ATTR_EAN = 'ean';
    public const PARAMETER_PRODUCT_ATTR_BRAND = 'brand';
    public const PARAMETER_PRODUCT_ATTR_URL = 'url_key';
}
