<?php

namespace Tests;

/**
 * Class RequestorTest
 *
 * @package Tests
 */
class RequestorTest extends BaseTestCase
{
    /**
     * Should return the right class name
     * @return void
     */
    public function testShouldTestRequestorParams()
    {
        $requestor = new \Fedapay\Requestor;
        $this->assertEquals($requestor->getApiKey(), 'sk_test_123');
        $this->assertEquals($requestor->getApiVersion(), 'v1');
        $this->assertEquals($requestor->getEnvironment(), 'sandbox');

        $requestor = new \Fedapay\Requestor('sk_test_myapikey', 'live', 'v2');
        $this->assertEquals($requestor->getApiKey(), 'sk_test_myapikey');
        $this->assertEquals($requestor->getApiVersion(), 'v2');
        $this->assertEquals($requestor->getEnvironment(), 'live');

        $requestor->setApiKey('sk_test_anotherapikey');
        $requestor->setApiVersion('v3');
        $requestor->setEnvironment('production');

        $this->assertEquals($requestor->getApiKey(), 'sk_test_anotherapikey');
        $this->assertEquals($requestor->getApiVersion(), 'v3');
        $this->assertEquals($requestor->getEnvironment(), 'production');
    }

    public function testRequestParams()
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
            $this->assertEquals($uri, 'https://api.fedapay.com/v1/path?foo=2');
            $this->assertEquals($httpRequest->getMethod(), 'GET');
            $this->assertContains('Bearer sk_test_123', $httpRequest->getHeader('Authorization'));
            $this->assertContains('1.0.0', $httpRequest->getHeader('X-Version'));
            $this->assertContains('PhpLib', $httpRequest->getHeader('X-Source'));
            $this->assertContains('foo', $httpRequest->getHeader('X-Custom'));
        }
    }
}
