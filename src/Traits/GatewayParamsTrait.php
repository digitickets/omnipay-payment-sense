<?php

namespace Omnipay\PaymentSense\Traits;

/**
 * Parameters that can be set at the gateway class, and so
 * must also be available at the request message class.
 */
trait GatewayParamsTrait
{
    /**
     * @param $value
     *
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setPreSharedKey($value)
    {
        return $this->setParameter('preSharedKey', $value);
    }

    /**
     * @return mixed
     */
    public function getPreSharedKey()
    {
        return $this->getParameter('preSharedKey');
    }
}
