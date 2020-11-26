<?php

namespace FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Model;

interface GoogleTagManageQuoteConnectorModelInterface
{
    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getTransactionAffiliation(string $page, array $params): array;

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getTransactionTotal(string $page, array $params): array;

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getTransactionTotalWithoutShippingAmount(string $page, array $params): array;

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getTransactionTax(string $page, array $params): array;

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getTransactionProductsSkus(string $page, array $params): array;

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getCustomerEmail(string $page, array $params): array;

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getTransactionEntity(string $page, array $params): array;
}
