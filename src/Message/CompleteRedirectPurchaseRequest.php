<?php

namespace Omnipay\PaymentSense\Message;

use function array_key_exists;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\PaymentSense\Helper;

class CompleteRedirectPurchaseRequest extends AbstractPurchaseRequest
{
    public const HASH_DIGEST = 'HashDigest';

    public const MERCHANT_ID = 'MerchantID';
    public const STATUS_CODE = 'StatusCode';
    public const MESSAGE = 'Message';
    public const PREVIOUS_STATUS_CODE = 'PreviousStatusCode';
    public const PREVIOUS_MESSAGE = 'PreviousMessage';
    public const CROSS_REFERENCE = 'CrossReference';
    public const ADDRESS_NUMERIC_CHECK_RESULT = 'AddressNumericCheckResult';
    public const POST_CODE_CHECK_RESULT = 'PostCodeCheckResult';
    public const CV2_CHECK_RESULT = 'CV2CheckResult';
    public const THREE_D_SECURE_AUTHENTICATION_CHECK_RESULT = 'ThreeDSecureAuthenticationCheckResult';

    public const CARD_TYPE = 'CardType';
    public const CARD_CLASS = 'CardClass';
    public const CARD_ISSUER = 'CardIssuer';
    public const CARD_ISSUER_COUNTRY_CODE = 'CardIssuerCountryCode';
    public const CARD_NUMBER_FIRST_SIX = 'CardNumberFirstSix';
    public const CARD_NUMBER_LAST_FOUR = 'CardNumberLastFour';
    public const CARD_EXPIRY_DATE = 'CardExpiryDate';

    public const AMOUNT = 'Amount';
    public const DONATION_AMOUNT = 'DonationAmount';

    public const CURRENCY_CODE = 'CurrencyCode';
    public const ORDER_ID = 'OrderID';
    public const TRANSACTION_TYPE = 'TransactionType';
    public const TRANSACTION_DATE_TIME = 'TransactionDateTime';
    public const ORDER_DESCRIPTION = 'OrderDescription';
    public const CUSTOMER_NAME = 'CustomerName';

    public const ADDRESS1 = 'Address1';
    public const ADDRESS2 = 'Address2';
    public const ADDRESS3 = 'Address3';
    public const ADDRESS4 = 'Address4';
    public const CITY = 'City';
    public const STATE = 'State';
    public const POST_CODE = 'PostCode';
    public const COUNTRY_CODE = 'CountryCode';
    public const EMAIL_ADDRESS = 'EmailAddress';

    public const PHONE_NUMBER = 'PhoneNumber';
    public const DATE_OF_BIRTH = 'DateOfBirth';
    public const PRIMARY_ACCOUNT_NAME = 'PrimaryAccountName';
    public const PRIMARY_ACCOUNT_NUMBER = 'PrimaryAccountNumber';
    public const PRIMARY_ACCOUNT_DATE_OF_BIRTH = 'PrimaryAccountDateOfBirth';
    public const PRIMARY_ACCOUNT_POST_CODE = 'PrimaryAccountPostCode';

    /**
     * This method will verify if the received information from the provider
     * is valid by comparing the recived HASH with the one we calculate locally.
     *
     * It returns all the infromation found on the provider's request
     *
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidResponseException
     */
    public function getData(): array
    {

        $data = $this->validatedRequestData();

        return $data;
    }

    /**
     * Validates whether the current request data is valid.
     *
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidResponseException
     */
    private function validatedRequestData()
    {
        $data = $this->httpRequest->request->all();

        $sortedData = [
            static::STATUS_CODE => true,
            static::MESSAGE => true,
            static::PREVIOUS_STATUS_CODE => true,
            static::PREVIOUS_MESSAGE => true,
            static::CROSS_REFERENCE => true,
            static::ADDRESS_NUMERIC_CHECK_RESULT => false,
            static::POST_CODE_CHECK_RESULT => false,
            static::CV2_CHECK_RESULT => false,
            static::THREE_D_SECURE_AUTHENTICATION_CHECK_RESULT => false,
            static::CARD_TYPE => false,
            static::CARD_CLASS => false,
            static::CARD_ISSUER => false,
            static::CARD_ISSUER_COUNTRY_CODE => false,
            static::CARD_NUMBER_FIRST_SIX => false,
            static::CARD_NUMBER_LAST_FOUR => false,
            static::CARD_EXPIRY_DATE => false,
            static::AMOUNT => true,
            static::DONATION_AMOUNT => false,
            static::CURRENCY_CODE => true,
            static::ORDER_ID => true,
            static::TRANSACTION_TYPE => true,
            static::TRANSACTION_DATE_TIME => true,
            static::ORDER_DESCRIPTION => true,
            static::CUSTOMER_NAME => true,
            static::ADDRESS1 => true,
            static::ADDRESS2 => true,
            static::ADDRESS3 => true,
            static::ADDRESS4 => true,
            static::CITY => true,
            static::STATE => true,
            static::POST_CODE => true,
            static::COUNTRY_CODE => true,
            static::EMAIL_ADDRESS => false,
            static::PHONE_NUMBER => false,
            static::DATE_OF_BIRTH => false,
            static::PRIMARY_ACCOUNT_NAME => false,
            static::PRIMARY_ACCOUNT_NUMBER => false,
            static::PRIMARY_ACCOUNT_DATE_OF_BIRTH => false,
            static::PRIMARY_ACCOUNT_POST_CODE => false,
        ];

        foreach ($sortedData as $key => $required) {
            if ($required) {
                $finalSortedData[$key] = $data[$key] ?? '';
            } elseif (array_key_exists($key, $data)) {
                $finalSortedData[$key] = $data[$key];
            }
        }

        $hashRequest = Helper::createSignature(
            Helper::generateStringToHash(
                $finalSortedData,
                $this->getMerchantId(),
                $this->getPassword(),
                $this->getPreSharedKey(),
                'SHA1'
            ),
            $this->getPreSharedKey(),
            'SHA1'
        );

        if ($data[static::HASH_DIGEST] !== $hashRequest) {
            throw new InvalidResponseException;
        }

        return $data;
    }

    /**
     * We don't need to send anything back to the provider so we just return
     * a CompleteRedirectPurchaseResponse with the data coming from the
     * provider's request (callback)
     *
     * @param mixed $data
     *
     * @return CompleteRedirectPurchaseResponse
     */
    public function sendData($data): CompleteRedirectPurchaseResponse
    {
        return $this->response = new CompleteRedirectPurchaseResponse(
            $this,
            $data
        );
    }
}
