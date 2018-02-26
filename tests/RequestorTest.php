<?php

namespace Tests;

/**
 * Class RequestorTest
 *
 * @package Tests
 */
class RequestorTest extends BaseTestCase
{
    public function testRequestDefaultParams()
    {
        $requestor = new \Fedapay\Requestor;
        $client = $this->createMockClient(500);
        $requestor->setClient($client);

        try {
            $requestor->request('get', '/path', ['foo' => '2'], ['X-Custom' => 'foo']);
        } catch (\Fedapay\Error\ApiConnection $e) {
            $httpRequest = $e->getHttpRequest();
            $httpResponse = $e->getHttpResponse();
            $httpStatus = $e->getHttpStatus();
            $this->assertNotNull($httpStatus, 500);
            $this->assertNotNull($httpResponse);
            $this->assertNotNull($httpRequest);
            $uri = $httpRequest->getUri() . '';
            $this->assertEquals($uri, 'https://sdx-api.fedapay.com/v1/path?foo=2');
            $this->assertEquals($httpRequest->getMethod(), 'GET');
            $this->assertContains('Bearer sk_test_123', $httpRequest->getHeader('Authorization'));
            $this->assertContains('1.0.0', $httpRequest->getHeader('X-Version'));
            $this->assertContains('PhpLib', $httpRequest->getHeader('X-Source'));
            $this->assertContains('foo', $httpRequest->getHeader('X-Custom'));
        }
    }

    public function testRequestSetParams()
    {
        \Fedapay\Fedapay::setApiKey(null);
        \Fedapay\Fedapay::setApiVersion('v3');
        \Fedapay\Fedapay::setEnvironment('production');
        \Fedapay\Fedapay::setToken('mytoken');
        \Fedapay\Fedapay::setAccountId(898);

        $requestor = new \Fedapay\Requestor;
        $client = $this->createMockClient(500);
        $requestor->setClient($client);

        try {
            $requestor->request('get', '/path', ['foo' => '2'], ['X-Custom' => 'foo']);
        } catch (\Fedapay\Error\ApiConnection $e) {
            $httpRequest = $e->getHttpRequest();
            $httpResponse = $e->getHttpResponse();
            $httpStatus = $e->getHttpStatus();
            $this->assertNotNull($httpStatus, 500);
            $this->assertNotNull($httpResponse);
            $this->assertNotNull($httpRequest);
            $uri = $httpRequest->getUri() . '';
            $this->assertEquals($uri, 'https://api.fedapay.com/v3/path?foo=2');
            $this->assertEquals($httpRequest->getMethod(), 'GET');
            $this->assertContains('Bearer mytoken', $httpRequest->getHeader('Authorization'));
            $this->assertContains('1.0.0', $httpRequest->getHeader('X-Version'));
            $this->assertContains('PhpLib', $httpRequest->getHeader('X-Source'));
            $this->assertContains('foo', $httpRequest->getHeader('X-Custom'));
            $this->assertContains(898, $httpRequest->getHeader('Fedapay-Account'));
        }
    }
}
