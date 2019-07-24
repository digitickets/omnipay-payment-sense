<?php

namespace Omnipay\PaymentSense\Test\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\PaymentSense\Message\CompleteRedirectPurchaseRequest;
use Omnipay\Tests\TestCase;
use ReflectionObject;
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
        $requestMock = $this->getMockHttpRequest('CompletePurchaseSuccess.txt');
        parse_str($requestMock->getBody()->getContents(), $data);

        return new Request([], $data, [], [], [], [],
            $requestMock->getBody()->getContents());
    }

    /**
     * Get a mock response for a client by mock file name
     *
     * @param string $path Relative path to the mock response file
     *
     * @return ResponseInterface
     */
    public function getMockHttpRequest($path)
    {
        if ($path instanceof RequestInterface) {
            return $path;
        }

        $ref = new ReflectionObject($this);
        $dir = dirname($ref->getFileName());

        // if mock file doesn't exist, check parent directory
        if (!file_exists($dir . '/Mock/' . $path) && file_exists($dir . '/../Mock/' . $path)) {
            return \GuzzleHttp\Psr7\parse_request(file_get_contents($dir . '/../Mock/' . $path));
        }

        return \GuzzleHttp\Psr7\parse_request(file_get_contents($dir . '/Mock/' . $path));
    }
}