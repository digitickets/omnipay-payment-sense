<?php

namespace Omnipay\PaymentSense\Message;

use Omnipay\Common\Message\RequestInterface;

class CompleteRedirectPurchaseResponse extends AbstractPurchaseResponse
{
    const STATUS_CODE = 'StatusCode';
    const MESSAGE = 'Message';
    const CROSS_REFERENCE = 'CrossReference';

    const RESULT_SUCCESS = '0';

    /**
     * @param RequestInterface $request
     * @param array            $data
     */
    public function __construct(RequestInterface $request, array $data = [])
    {
        $this->request = $request;
        $this->data = $data;
    }

    /**
     * isRedirect
     *
     * @return bool
     */
    public function isRedirect(): bool
    {
        return false;
    }

    /**
     * We expect 'RESULT' on the callback to be '00' to be successful
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return isset($this->data[static::STATUS_CODE])
            && static::RESULT_SUCCESS === $this->data[static::STATUS_CODE];
    }

    /**
     * getMessage
     */
    public function getMessage()
    {
        return isset($this->data[static::MESSAGE]) ?
            $this->data[static::MESSAGE] : null;
    }

    /**
     * getTransactionReference
     */
    public function getTransactionReference()
    {
        return isset($this->data[static::CROSS_REFERENCE]) ?
            $this->data[static::CROSS_REFERENCE] : null;
    }

    /**
     * Gets the redirect target url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return null;
    }

    /**
     * Get the required redirect method (either GET or POST).
     *
     * @return string
     */
    public function getRedirectMethod()
    {
        return null;
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     *
     * @return array
     */
    public function getRedirectData()
    {
        return null;
    }
}
