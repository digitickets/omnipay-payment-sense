<?php

namespace Omnipay\PaymentSense\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\GlobalPayments\Traits\ResponseFieldsTrait;

abstract class AbstractPurchaseResponse extends AbstractResponse implements
    RedirectResponseInterface
{
    /**
     * Returns true if the transaction is successful and complete.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return false;
    }
}
