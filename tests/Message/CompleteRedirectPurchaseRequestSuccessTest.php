<?php

namespace Omnipay\PaymentSense\Test\Message;

use Omnipay\Common\Message\RequestInterface;

use Omnipay\PaymentSense\Message\CompleteRedirectPurchaseRequest;
use Omnipay\PaymentSense\Message\CompleteRedirectPurchaseResponse;

use Omnipay\Tests\TestCase;
use ReflectionObject;
use Symfony\Component\HttpFoundation\Request;

class CompleteRedirectPurchaseRequestSuccessTest extends TestCase
{

    /** @var  \Omnipay\PaymentSense\Message\CompleteRedirectPurchaseRequest */
    private $request;

    /**
     * Setup
     */
    protected function setUp(): void
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