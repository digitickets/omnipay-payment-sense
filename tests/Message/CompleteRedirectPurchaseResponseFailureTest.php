<?php

namespace Omnipay\PaymentSense\Test\Message;

use Omnipay\PaymentSense\Message\CompleteRedirectPurchaseRequest;
use function Omnipay\PaymentSense\Test\Gateway\getMockHttpRequest;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CompleteRedirectPurchaseResponseFailureTest extends TestCase
{

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testInvalidResponse()
    {
        $request = $this->getRequestFromFile('CompletePurchaseHashFailure.txt');

        $response = $request->send();
        $response->isSuccessful();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testTransactionReferenceMissing()
    {
        $request = $this->getRequestFromFile('CompletePurchaseReferenceFailure.txt');
        $response = $request->send();
        $this->assertNull(
            $response->getTransactionReference()
        );
    }

    /**
     * @param string $fixture
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequestFromFile(string $fixture)
    {
        $requestMock = getMockHttpRequest($fixture);
        parse_str($requestMock['body'], $data);

        $request = new CompleteRedirectPurchaseRequest(
            $this->getHttpClient(),
            new Request([], $data, [], [], [], [],
                $requestMock['body'])
        );

        $request->setMerchantId('Totall-2560328');
        $request->setPassword('2OL9csT2THW3k5k');
        $request->setPreSharedKey('HlE6DLYJq4DoBjzLaM1y1LXyT2ob1nS2LRt12fS7ZA==');

        return $request;
    }
}