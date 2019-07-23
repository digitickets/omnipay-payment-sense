<?php

namespace Omnipay\PaymentSense\Message;

use function is_numeric;
use League\ISO3166\Exception\InvalidArgumentException;
use League\ISO3166\ISO3166;
use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\PaymentSense\Gateway;
use Omnipay\PaymentSense\Helper;
use function strlen;
use Omnipay\Common\Message\ResponseInterface;

class RedirectPurchaseRequest extends AbstractPurchaseRequest
{
    public const AMOUNT = 'Amount';
    public const CURRENCY_CODE = 'CurrencyCode';
    public const ORDER_ID = 'OrderID';
    public const TRANSACTION_TYPE = 'TransactionType';
    public const TRANSACTION_DATE_TIME = 'TransactionDateTime';
    public const CALLBACK_URL = 'CallbackURL';
    public const ORDER_DESCRIPTION = 'OrderDescription';
    public const CUSTOMER_NAME = 'CustomerName';
    public const EMAIL_ADDRESS = 'EmailAddress';
    public const ADDRESS1 = 'Address1';
    public const ADDRESS2 = 'Address2';
    public const ADDRESS3 = 'Address3';
    public const ADDRESS4 = 'Address4';
    public const CITY = 'City';
    public const STATE = 'State';
    public const POST_CODE = 'PostCode';
    public const COUNTRY_CODE = 'CountryCode';
    public const CV2_MANDATORY = 'CV2Mandatory';
    public const ADDRESS1_MANDATORY = 'Address1Mandatory';
    public const CITY_MANDATORY = 'CityMandatory';
    public const POST_CODE_MANDATORY = 'PostCodeMandatory';
    public const STATE_MANDATORY = 'StateMandatory';
    public const COUNTRY_MANDATORY = 'CountryMandatory';
    public const RESULT_DELIVERY_METHOD = 'ResultDeliveryMethod';
    public const SERVER_RESULT_URL = 'ServerResultURL';
    public const PAYMENT_FORM_DISPLAYS_RESULT = 'PaymentFormDisplaysResult';
    public const SERVER_RESULT_URL_COOKIE_VARIABLES = 'ServerResultURLCookieVariables';
    public const SERVER_RESULT_URL_FORM_VARIABLES = 'ServerResultURLFormVariables';
    public const SERVER_RESULT_URL_QUERY_STRING_VARIABLES = 'ServerResultURLQueryStringVariables';
    public const ECHO_CARD_TYPE = 'EchoCardType';
    public const EMAIL_ADDRESS_EDITABLE = 'EmailAddressEditable';
    public const PHONE_NUMBER_EDITABLE = 'PhoneNumberEditable';
    public const PHONE_NUMBER = 'PhoneNumber';

    public const HASH_DIGEST = 'HashDigest';

    /**
     * Returns the data on the request.
     *
     * It calculates the HASH that we need to send to the provider as well.
     *
     * @return array
     */
    public function getData(): array
    {
        $card = $this->getCard();

        if (!$card) {
            $card = new CreditCard();
        }

        $data = [
            static::AMOUNT => (int)round($this->getAmount() * 100),
            static::CURRENCY_CODE => $this->getCurrencyNumeric(),
            static::ECHO_CARD_TYPE => $this->getEchoCardType(),
            static::ORDER_ID => $this->getTransactionId(),
            static::TRANSACTION_TYPE => 'SALE',
            static::TRANSACTION_DATE_TIME => gmdate('Y-m-d H:i:s P'),
            static::CALLBACK_URL => $this->getReturnUrl(),
            static::ORDER_DESCRIPTION => $this->getDescription(),

            static::CUSTOMER_NAME => $card->getName(),
            static::ADDRESS1 => $card->getAddress1(),
            static::ADDRESS2 => $card->getAddress2(),
            static::ADDRESS3 => '',
            static::ADDRESS4 => '',

            static::CITY => $card->getCity(),
            static::STATE => $card->getState(),
            static::POST_CODE => $card->getPostcode(),
            static::COUNTRY_CODE => $this->getCountry(
                $card->getCountry(),
                'numeric'
            ),
            static::EMAIL_ADDRESS => $card->getEmail(),
            static::PHONE_NUMBER => $card->getPhone(),

            static::EMAIL_ADDRESS_EDITABLE => $this->getEmailAddressEditable(),
            static::PHONE_NUMBER_EDITABLE => $this->getPhoneNumberEditable(),

            static::CV2_MANDATORY => $this->getCV2Mandatory(),
            static::ADDRESS1_MANDATORY => $this->getAddress1Mandatory(),
            static::CITY_MANDATORY => $this->getCityMandatory(),
            static::POST_CODE_MANDATORY => $this->getPostCodeMandatory(),
            static::STATE_MANDATORY => $this->getStateMandatory(),
            static::COUNTRY_MANDATORY => $this->getCountryMandatory(),
            static::RESULT_DELIVERY_METHOD => $this->getResultDeliveryMethod(),
            static::SERVER_RESULT_URL => $this->getServerResultURL(),
            static::PAYMENT_FORM_DISPLAYS_RESULT => $this->getPaymentFormDisplaysResult(),
            static::SERVER_RESULT_URL_COOKIE_VARIABLES =>
                $this->getServerResultURLCookieVariables(),
            static::SERVER_RESULT_URL_FORM_VARIABLES => $this->getServerResultURLFormVariables(),
            static::SERVER_RESULT_URL_QUERY_STRING_VARIABLES => $this->getServerResultURLQueryStringVariables(),
        ];

        $data[static::HASH_DIGEST] =
            Helper::createSignature(
                Helper::generateStringToHash(
                    $data,
                    $this->getMerchantId(),
                    $this->getPassword(),
                    $this->getPreSharedKey(),
                    'SHA1'
                ),
                $this->getPreSharedKey(),
                'SHA1'
            );

        $data[Gateway::MERCHANT_ID] = $this->getMerchantId();

        return $data;
    }

    /**
     * $expectedFormat = name | alpha2 | alpha3 | numeric | currency
     *
     * @param        $country
     * @param string $expectedFormat
     *
     * @return array
     */
    public function getCountry($country, string $expectedFormat = null)
    {
        try {
            if (is_numeric($country)) {
                $data = (new ISO3166)->numeric($country);
            } elseif (strlen($country) == 3) {
                $data = (new ISO3166)->alpha3($country);
            } elseif (strlen($country) == 2) {
                $data = (new ISO3166)->alpha2($country);
            } else {
                $data = (new ISO3166)->name($country);
            }
        } catch (InvalidArgumentException $e) {
            $data = null;
        }

        if ($expectedFormat && $data) {
            $data = $data[$expectedFormat];
        }

        return $data;
    }

    /**
     * Validate the request.
     *
     * This method is called internally by gateways to avoid wasting time with
     * an API call when the request is clearly invalid.
     *
     * @param string ... a variable length list of required parameters
     *
     * @throws InvalidRequestException
     */
    public function validate(): void
    {
        $this->httpRequest->request;
        foreach (func_get_args() as $key) {
            $value = $this->parameters->get($key);
            if (!isset($value)) {
                throw new InvalidRequestException("The $key parameter is required");
            }
        }
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data): ResponseInterface
    {

        return new RedirectPurchaseResponse($this, $data);
    }

}
