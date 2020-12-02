<?php

namespace FondOfSpryker\Shared\GoogleTagManagerQuoteConnector;

interface GoogleTagManagerQuoteConnectorConstants
{
    public const FIELD_TRANSACTION_AFFILIATION = 'transactionAffiliation';
    public const FIELD_TRANSACTION_TOTAL = 'transactionTotal';
    public const FIELD_TRANSACTION_WITHOUT_SHIPPING_AMOUNT = 'transactionTotalWithoutShippingAmount';
    public const FIELD_TRANSACTION_TAX = 'transactionTax';
    public const FIELD_TRANSACTION_PRODUCTS_SKUS = 'transactionProductsSkus';
    public const FIELD_TRANSACTION_ENTITY = 'transactionEntity';
    public const FIELD_CUSTOMER_EMAIL = 'customerEmail';
    public const FIELD_EXTERNAL_ID_HASH = 'externalIdHash';
    public const FIELD_TRANSACTION_PRODUCTS = 'transactionProducts';

    public const PARAMETER_QUOTE = 'PARAMETER_QUOTE';
}
