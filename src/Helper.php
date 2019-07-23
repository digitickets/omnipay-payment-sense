<?php
/**
 * Created by PhpStorm.
 * User: Gabo
 * Date: 22/07/2019
 * Time: 13:20
 */

namespace Omnipay\PaymentSense;

class Helper
{

    /**
     * Given an associative array, it will return a querystring
     * representation without any kind of encoding
     *
     * param1=some value&param2=other value
     *
     * @param array $parameters
     *
     * @return string
     */
    public static function arrayToQueryString(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $arrValues[] = "{$key}={$value}";
        }

        return implode('&', $arrValues);
    }

    /**
     * Given an array it will generate the digest based on the $hashMethod
     * passed.
     *
     * $preSharedKey is mandatory when the encoding is either MD5 or SHA1
     *
     * @param        $data
     * @param        $merchantId
     * @param        $password
     * @param        $preSharedKey
     * @param string $hashMethod
     *
     * @return string
     */
    public static function generateStringToHash(
        $data,
        $merchantId,
        $password,
        $preSharedKey = null,
        $hashMethod = 'SHA1'
    ) {
        $includePreSharedKeyInString = false;
        switch ($hashMethod) {
            case 'MD5':
                $includePreSharedKeyInString = true;
                break;
            case 'SHA1':
                $includePreSharedKeyInString = true;
                break;
            case 'HMACMD5':
                $includePreSharedKeyInString = false;
                break;
            case 'HMACSHA1':
                $includePreSharedKeyInString = false;
                break;
        }

        $header = [];

        if ($includePreSharedKeyInString) {
            $header[Gateway::PRE_SHARED_KEY] = $preSharedKey;
        }

        $header[Gateway::MERCHANT_ID] = $merchantId;
        $header[Gateway::PASSWORD] = $password;
        
        return self::arrayToQueryString($header + $data);
    }

    /**
     * Creates the signature needed to send to the provider
     *
     * @param        $inputString
     * @param        $preSharedKey
     * @param string $hashMethod
     *
     * @return string
     * @internal param array $data
     * @internal param string $method
     *
     */
    public static function createSignature(
        $inputString,
        $preSharedKey,
        $hashMethod = 'SHA1'
    ): string {
        switch ($hashMethod) {
            case 'MD5':
                $hashDigest = md5($inputString);
                break;
            case 'SHA1':
                $hashDigest = sha1($inputString);
                break;
            case 'HMACMD5':
                $hashDigest = hash_hmac('md5', $inputString, $preSharedKey);
                break;
            case 'HMACSHA1':
                $hashDigest = hash_hmac('sha1', $inputString,
                    $preSharedKey);
                break;
        }

        return ($hashDigest);
    }

}