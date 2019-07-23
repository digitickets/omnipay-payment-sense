<?php

namespace Omnipay\PaymentSense\Test\Gateway;

use Omnipay\PaymentSense\Message\RedirectPurchaseRequest;
use Omnipay\PaymentSense\Message\RedirectPurchaseResponse;
use Omnipay\Tests\GatewayTestCase;
use Omnipay\PaymentSense\Gateway;

class PaymentSenseTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\PaymentSense\Gateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $cardData = null;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->gateway = new Gateway(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->options = [
            'merchantId' => 'Totall-2560328',
            'password' => '2OL9csT2THW3k5k',
            'preSharedKey' => 'HlE6DLYJq4DoBjzLaM1y1LXyT2ob1nS2LRt12fS7ZA==',
            'amount' => 400.00,
            'currency' => 'EUR',
            'returnUrl' => '/callback-url',
            'transactionId' => uniqid(),
        ];

        $this->cardData = [
            'number' => '4263970000005262',
            'expiryMonth' => 12,
            'expiryYear' => 22,
            'cvv' => 123,
            'email' => 'frank@newyork.com',
            'billingFirstName' => 'Frank',
            'billingLastName' => 'Sinatra',
            'billingPhone' => '1234567890',
            'billingAddress1' => 'Address 1',
            'billingAddress2' => 'Address 2',
            'billingCity' => 'London',
            'billingPostCode' => 'NW1 9PH',
            'billingState' => 'State',
            'billingCountry' => 'GB',
            'billingPhone' => 'Phone',
            'shippingAddress1' => 'Address 1',
            'shippingAddress2' => 'Address 2',
            'shippingCity' => 'London',
            'shippingPostcode' => 'NW1 9PH',
            'shippingState' => 'State',
            'shippingCountry' => 'GB',
            'shippingPhone' => 'Phone',
        ];

    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase(
            array_merge($this->options, [
                'card' => $this->cardData,
                'description' => 'Purchase test',
            ]))->send();

        $this->assertInstanceOf(
            RedirectPurchaseResponse::class,
            $response
        );

        $this->assertFalse($response->isPending());
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals(
            'https://mms.paymentsensegateway.com/Pages/PublicPages/PaymentForm.aspx',
            $response->getRedirectUrl()
        );
    }

    /**
     * @return \Omnipay\Common\Message\RequestInterface
     */
    public function testFormData()
    {
        $request = $this->gateway->purchase(
            array_merge($this->options, [
                'card' => $this->cardData,
                'transactionId' => uniqid(),
                'description' => 'Purchase test',
            ]));

        $params = $request->getData();

        $this->assertEquals(
            'Purchase test',
            $params[RedirectPurchaseRequest::ORDER_DESCRIPTION]
        );

        $this->assertEquals(
            'SALE',
            $params[RedirectPurchaseRequest::TRANSACTION_TYPE]
        );

        $this->assertNotNull(
            $params[RedirectPurchaseRequest::TRANSACTION_DATE_TIME]
        );

        $this->assertEquals(
            40000,
            $params[RedirectPurchaseRequest::AMOUNT]
        );

        $this->assertEquals('978',
            $params[RedirectPurchaseRequest::CURRENCY_CODE]
        );

        $this->assertEquals(
            $this->cardData['billingFirstName'] . ' ' . $this->cardData['billingLastName'],
            $params[RedirectPurchaseRequest::CUSTOMER_NAME]
        );

        $this->assertEquals(
            $this->cardData['email'],
            $params[RedirectPurchaseRequest::EMAIL_ADDRESS]
        );

        $this->assertEquals(
            $this->cardData['billingPhone'],
            $params[RedirectPurchaseRequest::PHONE_NUMBER]
        );

        $this->assertEquals(
            $this->cardData['billingAddress1'],
            $params[RedirectPurchaseRequest::ADDRESS1]
        );

        $this->assertEquals(
            $this->cardData['billingAddress2'],
            $params[RedirectPurchaseRequest::ADDRESS2]
        );

        $this->assertEquals(
            $this->cardData['billingCity'],
            $params[RedirectPurchaseRequest::CITY]
        );

        $this->assertEquals(
            $this->cardData['billingPostCode'],
            $params[RedirectPurchaseRequest::POST_CODE]
        );

        $this->assertEquals(
            $this->cardData['billingState'],
            $params[RedirectPurchaseRequest::STATE]
        );

        $this->assertEquals('826',
            $params[RedirectPurchaseRequest::COUNTRY_CODE]
        );

        $this->assertEquals(
            $this->cardData['shippingAddress1'],
            $params[RedirectPurchaseRequest::ADDRESS1]
        );

        $this->assertEquals(
            $this->cardData['shippingAddress2'],
            $params[RedirectPurchaseRequest::ADDRESS2]
        );

        $this->assertEquals(
            $this->cardData['shippingCity'],
            $params[RedirectPurchaseRequest::CITY]
        );

        $this->assertEquals(
            $this->cardData['shippingPostcode'],
            $params[RedirectPurchaseRequest::POST_CODE]
        );

        $this->assertEquals(
            $this->cardData['shippingState'],
            $params[RedirectPurchaseRequest::STATE]
        );

        return $request;

    }

    /**
     * @depends testFormData
     *
     * @param \Omnipay\PaymentSense\Message\RedirectPurchaseRequest $request
     *
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function testRedirectRequest(RedirectPurchaseRequest $request)
    {

        $response = $request->send();
        $this->assertInstanceOf(RedirectPurchaseResponse::class, $response);

        return $response;

    }

    /**
     * @depends testRedirectRequest
     *
     * @param $response
     */
    public function testRedirectResponse($response)
    {
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('POST', $response->getRedirectMethod());
    }
}
