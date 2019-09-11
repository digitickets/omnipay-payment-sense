<?php

namespace Omnipay\PaymentSense\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PaymentSense\Traits\GatewayParamsTrait;

abstract class AbstractPurchaseRequest extends AbstractRequest
{
    use GatewayParamsTrait;

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

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setEchoCardType($value)
    {
        return $this->setParameter('EchoCardType', $value);
    }

    /**
     * @return mixed|string
     */
    public function getEchoCardType()
    {
        return 'False';

        return $this->getParameter('EchoCardType');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setEmailAddressEditable($value)
    {
        return $this->setParameter('EmailAddressEditable', $value);
    }

    /**
     * @return mixed|string
     */
    public function getEmailAddressEditable()
    {
        return 'False';

        return $this->getParameter('EmailAddressEditable');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setPhoneNumberEditable($value)
    {
        return $this->setParameter('PhoneNumberEditable', $value);
    }

    /**
     * @return mixed|string
     */
    public function getPhoneNumberEditable()
    {
        return 'False';

        return $this->getParameter('PhoneNumberEditable');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setCV2Mandatory($value)
    {
        return $this->setParameter('CV2Mandatory', $value);
    }

    /**
     * @return mixed|string
     */
    public function getCV2Mandatory()
    {
        return 'False';

        return $this->getParameter('CV2Mandatory');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setAddress1Mandatory($value)
    {
        return $this->setParameter('Address1Mandatory', $value);
    }

    /**
     * @return mixed|string
     */
    public function getAddress1Mandatory()
    {
        return 'False';

        return $this->getParameter('Address1Mandatory');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setCityMandatory($value)
    {
        return $this->setParameter('CityMandatory', $value);
    }

    /**
     * @return mixed|string
     */
    public function getCityMandatory()
    {
        return 'False';

        return $this->getParameter('CityMandatory');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setPostCodeMandatory($value)
    {
        return $this->setParameter('PostCodeMandatory', $value);
    }

    /**
     * @return mixed|string
     */
    public function getPostCodeMandatory()
    {
        return 'False';

        return $this->getParameter('PostCodeMandatory');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setStateMandatory($value)
    {
        return $this->setParameter('StateMandatory', $value);
    }

    /**
     * @return mixed|string
     */
    public function getStateMandatory()
    {
        return 'False';

        return $this->getParameter('StateMandatory');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setCountryMandatory($value)
    {
        return $this->setParameter('CountryMandatory', $value);
    }

    /**
     * @return mixed|string
     */
    public function getCountryMandatory()
    {
        return 'False';

        return $this->getParameter('CountryMandatory');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setResultDeliveryMethod($value)
    {
        return $this->setParameter('ResultDeliveryMethod', $value);
    }

    /**
     * @return mixed|string
     */
    public function getResultDeliveryMethod()
    {
        if (!$this->getParameter('ResultDeliveryMethod')) {
            return 'POST';
        }

        return $this->getParameter('ResultDeliveryMethod');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setServerResultURL($value)
    {
        return $this->setParameter('ServerResultURL', $value);
    }

    /**
     * @return mixed
     */
    public function getServerResultURL()
    {
        return $this->getParameter('ServerResultURL');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setPaymentFormDisplaysResult($value)
    {
        return $this->setParameter('PaymentFormDisplaysResult', $value);
    }

    /**
     * @return mixed
     */
    public function getPaymentFormDisplaysResult()
    {
        return $this->getParameter('PaymentFormDisplaysResult');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setServerResultURLCookieVariables($value)
    {
        return $this->setParameter('ServerResultURLCookieVariables', $value);
    }

    /**
     * @return mixed
     */
    public function getServerResultURLCookieVariables()
    {
        return $this->getParameter('ServerResultURLCookieVariables');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setServerResultURLFormVariables($value)
    {
        return $this->setParameter('ServerResultURLFormVariables', $value);
    }

    /**
     * @return mixed
     */
    public function getServerResultURLFormVariables()
    {
        return $this->getParameter('ServerResultURLFormVariables');
    }

    /**
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setServerResultURLQueryStringVariables($value)
    {
        return $this->setParameter('ServerResultURLQueryStringVariables',
            $value);
    }

    /**
     * @return mixed
     */
    public function getServerResultURLQueryStringVariables()
    {
        return $this->getParameter('ServerResultURLQueryStringVariables');
    }
}
