<?php

namespace Omnipay\PaymentSense\Test\Message;


use Omnipay\PaymentSense\Message\CompleteRedirectPurchaseRequest;
use Omnipay\PaymentSense\Message\CompleteRedirectPurchaseResponse;
use function Omnipay\PaymentSense\Test\Gateway\getMockHttpRequest;
use Omnipay\Tests\TestCase;
use function parse_str;
use Symfony\Component\HttpFoundation\Request;

class CompleteRedirectPurchaseRequestSuccessTest extends TestCase
{

    /** @var  \Omnipay\PaymentSense\Message\CompleteRedirectPurchaseRequest */
    private $request;

    /**
     * Setup
     */
    protected function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();

        $this->request = new CompleteRedirectPurchaseRequest($client, $request);
        $this->request->setMerchantId('Totall-2560328');
        $this->request->setPassword('2OL9csT2THW3k5k');
        $this->request->setPreSharedKey('HlE6DLYJq4DoBjzLaM1y1LXyT2ob1nS2LRt12fS7ZA==');
    }

    /**
     *
     */
    public function testGetData()
    {
        $this->assertNotNull($this->request->getData());
    }

    /**
     *
     */
    public function testCompletePurchaseSuccess()
    {
        $data = $this->request->getData();

        $this->assertEquals(
            CompleteRedirectPurchaseResponse::RESULT_SUCCESS,
            $data[CompleteRedirectPurchaseResponse::STATUS_CODE]
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