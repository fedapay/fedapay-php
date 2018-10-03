<?php

namespace Tests;

/**
 * Class RequestorTest
 *
 * @package Tests
 */
class RequestorTest extends BaseTestCase
{
    public function testHttpClientInjection()
    {
        $reflector = new \ReflectionClass('FedaPay\\Requestor');
        $method = $reflector->getMethod('httpClient');
        $method->setAccessible(true);
        $curl = new \FedaPay\HttpClient\CurlClient();
        $curl->setTimeout(10);
        \FedaPay\Requestor::setHttpClient($curl);
        $injectedCurl = $method->invoke(new \FedaPay\Requestor());
        $this->assertSame($injectedCurl, $curl);
    }

    public function testRequestDefaultParams()
    {
        $this->mockRequest(
            'get',
            '/v1/path',
            ['foo' => '2'],
            [],
            500,
            [
                'X-Custom' => 'foo'
            ]
        );
        $requestor = new \FedaPay\Requestor;

        $this->setExpectedException('\FedaPay\Error\ApiConnection');
        $requestor->request('get', '/path', ['foo' => '2'], ['X-Custom' => 'foo']);
    }

    public function testRequestSetParams()
    {
        \FedaPay\FedaPay::setApiKey(null);
        \FedaPay\FedaPay::setApiVersion('v3');
        \FedaPay\FedaPay::setEnvironment('production');
        \FedaPay\FedaPay::setToken('mytoken');
        \FedaPay\FedaPay::setAccountId(898);

        $this->mockRequest(
            'get',
            '/v3/path',
            ['foo' => '2'],
            [],
            500,
            [
                'Authorization' => 'Bearer mytoken',
                'FedaPay-Account' => 898,
                'X-Custom' => 'foo'
            ]
        );
        $requestor = new \FedaPay\Requestor;

        $this->setExpectedException('\FedaPay\Error\ApiConnection');
        $requestor->request('get', '/path', ['foo' => '2'], [
            'X-Custom' => 'foo'
        ]);
    }
}
