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
        $client = $this->createMockClient(500);
        \FedaPay\Requestor::setHttpClient($client);
        $requestor = new \FedaPay\Requestor;

        try {
            $requestor->request('get', '/path', ['foo' => '2'], ['X-Custom' => 'foo']);
        } catch (\FedaPay\Error\ApiConnection $e) {
            $httpRequest = $e->getHttpRequest();
            $httpResponse = $e->getHttpResponse();
            $httpStatus = $e->getHttpStatus();
            $this->assertEquals($httpStatus, 500);
            $this->assertNotNull($httpResponse);
            $this->assertNotNull($httpRequest);
            $uri = $httpRequest->getUri() . '';
            $this->assertEquals($uri, 'https://sdx-api.fedapay.com/v1/path?foo=2');
            $this->assertEquals($httpRequest->getMethod(), 'GET');
            $this->assertContains('Bearer sk_test_123', $httpRequest->getHeader('Authorization'));
            $this->assertContains(\FedaPay\FedaPay::VERSION, $httpRequest->getHeader('X-Version'));
            $this->assertContains('FedaPay PhpLib', $httpRequest->getHeader('X-Source'));
            $this->assertContains('foo', $httpRequest->getHeader('X-Custom'));
        }
    }

    public function testRequestSetParams()
    {
        \FedaPay\FedaPay::setApiKey(null);
        \FedaPay\FedaPay::setApiVersion('v3');
        \FedaPay\FedaPay::setEnvironment('production');
        \FedaPay\FedaPay::setToken('mytoken');
        \FedaPay\FedaPay::setAccountId(898);

        $client = $this->createMockClient(500);
        \FedaPay\Requestor::setHttpClient($client);
        $requestor = new \FedaPay\Requestor;

        try {
            $requestor->request('get', '/path', ['foo' => '2'], ['X-Custom' => 'foo']);
        } catch (\FedaPay\Error\ApiConnection $e) {
            $httpRequest = $e->getHttpRequest();
            $httpResponse = $e->getHttpResponse();
            $httpStatus = $e->getHttpStatus();
            $this->assertEquals($httpStatus, 500);
            $this->assertNotNull($httpResponse);
            $this->assertNotNull($httpRequest);
            $uri = $httpRequest->getUri() . '';
            $this->assertEquals($uri, 'https://api.fedapay.com/v3/path?foo=2');
            $this->assertEquals($httpRequest->getMethod(), 'GET');
            $this->assertContains('Bearer mytoken', $httpRequest->getHeader('Authorization'));
            $this->assertContains(\FedaPay\FedaPay::VERSION, $httpRequest->getHeader('X-Version'));
            $this->assertContains('FedaPay PhpLib', $httpRequest->getHeader('X-Source'));
            $this->assertContains('foo', $httpRequest->getHeader('X-Custom'));
            $this->assertContains(898, $httpRequest->getHeader('FedaPay-Account'));
        }
    }
}
