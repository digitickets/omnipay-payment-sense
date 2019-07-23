<?php

namespace Omnipay\PaymentSense\Message;

use Omnipay\Common\Message\AbstractRequest;

abstract class AbstractPurchaseRequest extends AbstractRequest
{
    /**
     * getMerchantId
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->getParameter('merchantId');
    }

    /**
     * setMerchantId
     *
     * @param [type] $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * getPreSharedKey
     * @return string
     */
    public function getPreSharedKey(): string
    {
        return $this->getParameter('sharedSecret');
    }

    /**
     * setPreSharedKey
     *
     * @param [type] $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setPreSharedKey($value)
    {
        return $this->setParameter('sharedSecret', $value);
    }

    /**
     * setPassword
     *
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * getPassword
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setTransactionType($value)
    {
        return $this->setParameter('transactionType', $value);
    }

    /**
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getTransactionType()
    {
        return $this->getParameter('transactionType');
    }

    public function setEchoCardType($value)
    {
        return $this->setParameter('EchoCardType', $value);
    }

    public function getEchoCardType()
    {
        return 'False';

        return $this->getParameter('EchoCardType');
    }

    public function setEmailAddressEditable($value)
    {
        return $this->setParameter('EmailAddressEditable', $value);
    }

    public function getEmailAddressEditable()
    {
        return 'False';

        return $this->getParameter('EmailAddressEditable');
    }

    public function setPhoneNumberEditable($value)
    {
        return $this->setParameter('PhoneNumberEditable', $value);
    }

    public function getPhoneNumberEditable()
    {
        return 'False';

        return $this->getParameter('PhoneNumberEditable');
    }

    public function setCV2Mandatory($value)
    {
        return $this->setParameter('CV2Mandatory', $value);
    }

    public function getCV2Mandatory()
    {
        return 'False';

        return $this->getParameter('CV2Mandatory');
    }

    public function setAddress1Mandatory($value)
    {
        return $this->setParameter('Address1Mandatory', $value);
    }

    public function getAddress1Mandatory()
    {
        return 'False';

        return $this->getParameter('Address1Mandatory');
    }

    public function setCityMandatory($value)
    {
        return $this->setParameter('CityMandatory', $value);
    }

    public function getCityMandatory()
    {
        return 'False';

        return $this->getParameter('CityMandatory');
    }

    public function setPostCodeMandatory($value)
    {
        return $this->setParameter('PostCodeMandatory', $value);
    }

    public function getPostCodeMandatory()
    {
        return 'False';

        return $this->getParameter('PostCodeMandatory');
    }

    public function setStateMandatory($value)
    {
        return $this->setParameter('StateMandatory', $value);
    }

    public function getStateMandatory()
    {
        return 'False';

        return $this->getParameter('StateMandatory');
    }

    public function setCountryMandatory($value)
    {
        return $this->setParameter('CountryMandatory', $value);
    }

    public function getCountryMandatory()
    {
        return 'False';

        return $this->getParameter('CountryMandatory');
    }

    public function setResultDeliveryMethod($value)
    {
        return $this->setParameter('ResultDeliveryMethod', $value);
    }

    public function getResultDeliveryMethod()
    {
        if (!$this->getParameter('ResultDeliveryMethod')) {
            return 'POST';
        }

        return $this->getParameter('ResultDeliveryMethod');
    }

    public function setServerResultURL($value)
    {
        return $this->setParameter('ServerResultURL', $value);
    }

    public function getServerResultURL()
    {
        return $this->getParameter('ServerResultURL');
    }

    public function setPaymentFormDisplaysResult($value)
    {
        return $this->setParameter('PaymentFormDisplaysResult', $value);
    }

    public function getPaymentFormDisplaysResult()
    {
        return $this->getParameter('PaymentFormDisplaysResult');
    }

    public function setServerResultURLCookieVariables($value)
    {
        return $this->setParameter('ServerResultURLCookieVariables', $value);
    }

    public function getServerResultURLCookieVariables()
    {
        return $this->getParameter('ServerResultURLCookieVariables');
    }

    public function setServerResultURLFormVariables($value)
    {
        return $this->setParameter('ServerResultURLFormVariables', $value);
    }

    public function getServerResultURLFormVariables()
    {
        return $this->getParameter('ServerResultURLFormVariables');
    }

    public function setServerResultURLQueryStringVariables($value)
    {
        return $this->setParameter('ServerResultURLQueryStringVariables',
            $value);
    }

    public function getServerResultURLQueryStringVariables()
    {
        return $this->getParameter('ServerResultURLQueryStringVariables');
    }
}
