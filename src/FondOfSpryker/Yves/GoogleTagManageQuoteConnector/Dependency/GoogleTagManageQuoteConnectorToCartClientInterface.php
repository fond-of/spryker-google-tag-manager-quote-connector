<?php

namespace FondOfSpryker\Yves\GoogleTagManageQuoteConnector\Dependency;

use Generated\Shared\Transfer\QuoteTransfer;

interface GoogleTagManageQuoteConnectorToCartClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote(): QuoteTransfer;
}
