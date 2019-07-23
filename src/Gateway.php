<?php

namespace Omnipay\PaymentSense;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\PaymentSense\Message\CompleteRedirectPurchaseRequest;
use Omnipay\PaymentSense\Message\RedirectPurchaseRequest;

/**
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options =[])
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array$options = [])
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options =[])
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options =[])
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = [])
 */
class Gateway extends AbstractGateway
{
    public const MERCHANT_ID = 'MerchantID';
    public const PRE_SHARED_KEY = 'PreSharedKey';
    public const PASSWORD = 'Password';
    
    /**
     * Get gateway name
     *
     * @return string
     */
    public function getName() : string
    {
        return 'SecureTrading';
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPreSharedKey($value)
    {
        return $this->setParameter('preSharedKey', $value);
    }

    public function getPreSharedKey()
    {
        return $this->getParameter('preSharedKey');
    }

    /**
     * Get gateway default parameters
     *
     * @return array
     */
    public function getDefaultParameters() : array
    {

        return [
            'merchantId' => null,
            'password' => true,
            'preSharedKey' => null,
        ];
    }

    /**
     * completePuchase function to be called on provider's callback
     *
     * @param array $options
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function completePurchase(array $options = []): RequestInterface
    {
        return $this->createRequest(
            CompleteRedirectPurchaseRequest::class,
            $options
        );
    }

    /**
     * puchase function to be called to initiate a purchase
     *
     * @param  array $parameters
     * @return RequestInterface
     */
    public function purchase(array $parameters = []): RequestInterface
    {
        $parameters = array_merge($this->getParameters(), $parameters);

        return $this->createRequest(
            RedirectPurchaseRequest::class,
            $parameters
        );
    }
}
