<?php

namespace FondOfSpryker\Yves\GoogleTagManagerQuoteConnector\Dependency;

use Generated\Shared\Transfer\QuoteTransfer;

interface GoogleTagManagerQuoteConnectorToCartClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote(): QuoteTransfer;
}
