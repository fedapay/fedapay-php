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

    public function testRequestParams()
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

        $this->expectException('\FedaPay\Error\ApiConnection');
        $requestor->request('get', '/path', ['foo' => '2'], ['X-Custom' => 'foo']);
    }

    public function testRequestSetParams()
    {
        \FedaPay\FedaPay::setApiKey(null);
        \FedaPay\FedaPay::setApiVersion('v3');
        \FedaPay\FedaPay::setEnvironment('production');
        \FedaPay\FedaPay::setToken('mytoken');
        \FedaPay\FedaPay::setAccountId(898);
        \FedaPay\FedaPay::setLocale('en');

        $this->mockRequest(
            'get',
            '/v3/path',
            ['foo' => '2', 'locale' => 'en'],
            [],
            500,
            [
                'Authorization' => 'Bearer mytoken',
                'FedaPay-Account' => 898,
                'X-Api-Version' => 'v3',
                'X-Custom' => 'foo'
            ]
        );
        $requestor = new \FedaPay\Requestor;

        $this->expectException('\FedaPay\Error\ApiConnection');
        $requestor->request('get', '/path', ['foo' => '2'], [
            'X-Custom' => 'foo'
        ]);
    }

    public function testShouldFaildParsingResponse()
    {
        $this->mockRequest(
            'get',
            '/v1/path',
            [],
            'unable to parse',
            200
        );
        $requestor = new \FedaPay\Requestor;

        $this->expectException('\FedaPay\Error\ApiConnection', 'unable to parse');
        $requestor->request('get', '/path');
    }

    public function testShouldParseApiErrors()
    {
        $this->mockRequest(
            'get',
            '/v1/path',
            [],
            ['message' => 'Error Message'],
            400
        );
        $requestor = new \FedaPay\Requestor;

        $this->expectException('\FedaPay\Error\ApiConnection', 'Error Message');
        $requestor->request('get', '/path');
    }
}
