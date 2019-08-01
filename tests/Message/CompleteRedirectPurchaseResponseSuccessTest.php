<?php

namespace Omnipay\PaymentSense\Test\Message;

use Omnipay\PaymentSense\Message\CompleteRedirectPurchaseRequest;
use function Omnipay\PaymentSense\Test\Gateway\getMockHttpRequest;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CompleteRedirectPurchaseResponseSuccessTest extends TestCase
{

    /** @var  \Omnipay\PaymentSense\Message\CompleteRedirectPurchaseResponse */
    private $response;

    /**
     * Setup
     */
    protected function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();
        $purchaseRequest = new CompleteRedirectPurchaseRequest(
            $client,
            $request
        );

        $purchaseRequest->setMerchantId('Totall-2560328');
        $purchaseRequest->setPassword('2OL9csT2THW3k5k');
        $purchaseRequest->setPreSharedKey('HlE6DLYJq4DoBjzLaM1y1LXyT2ob1nS2LRt12fS7ZA==');

        $this->response = $purchaseRequest->send();
    }

    /**
     *
     */
    public function testNoRedirection()
    {
        $this->assertFalse($this->response->isRedirect());
    }

    /**
     *
     */
    public function testSuccessfulResponse()
    {
        $this->assertTrue($this->response->isSuccessful());
    }

    /**
     *
     */
    public function testTransactionReferencePresent()
    {
        $this->assertEquals(
            '190723082319319602184245',
            $this->response->getTransactionReference()
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getHttpRequest()
    {
        $data = getMockHttpRequest('CompletePurchaseSuccess.txt');
        parse_str($data['body'], $body);

        return new Request([], $body, [], [], [], [],
            $data['body']);
    }
}