<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected $container;

    const API_KEY = 'sk_local_123';
    const OAUTH_TOKEN = 'oauth_test_token_123';
    const API_BASE = 'https://dev-api.fedapay.com';

    protected function setUp()
    {
        \FedaPay\FedaPay::setApiKey(self::API_KEY);
        \FedaPay\FedaPay::setApiBase(self::API_BASE);

         // Set up the HTTP client mocker
        $this->clientMock = $this->getMock('\FedaPay\HttpClient\ClientInterface');
    }

    protected function tearDown()
    {
        // Back to default
        \FedaPay\FedaPay::setApiKey(null);
        \FedaPay\FedaPay::setApiVersion('v1');
        \FedaPay\FedaPay::setEnvironment('sandbox');
        \FedaPay\FedaPay::setToken(null);
        \FedaPay\FedaPay::setAccountId(null);
        \FedaPay\FedaPay::setVerifySslCerts(true);
        \FedaPay\Requestor::setHttpClient(null);
    }

    /* public function createMockClient($status, $body = null, $headers = [])
    {
        $this->container = [];
        $history = Middleware::history($this->container);

        $body = json_encode($body);
        $response = new Response($status, $headers, $body);

        $mock = new MockHandler([$response]);
        $stack = HandlerStack::create($mock);
        // Add the history middleware to the handler stack.
        $stack->push($history);

        $client = new Client(['handler' => $stack]);

        return $client;
    }

    public function exceptRequest($path, $method, $query = null, $body = null)
    {
        // Iterate over the requests and responses
        foreach ($this->container as $transaction) {
            $request = $transaction['request'];


            $this->assertEquals($request->getUri()->getPath(), $path);
            $this->assertEquals($request->getMethod(), $method);

            if ($query) {
                parse_str($request->getUri()->getQuery(), $request_query);

                $this->assertArraySubset($query, $request_query);
            }

            if ($body) {
                $request_body = json_decode($request->getBody()->getContents(), true);
                $this->assertArraySubset($body, $request_body);
            }
        }
    } */

     /**
     * Sets up a request expectation with the provided parameters. The request
     * will actually go through and be emitted.
     *
     * @param string $method HTTP method (e.g. 'post', 'get', etc.)
     * @param string $path relative path (e.g. '/v1/transactions')
     * @param array|null $params array of parameters. If null, parameters will
     *   not be checked.
     * @param string[]|null $headers array of headers. Does not need to be
     *   exhaustive. If null, headers are not checked.
     */
    protected function expectsRequest(
        $method,
        $path,
        $params = null,
        $headers = null
    ) {
        $this->prepareRequestMock($method, $path, $params, $headers)
            ->will($this->returnCallback(
                function ($method, $absUrl, $headers, $params) {
                    $curlClient = \FedaPay\HttpClient\CurlClient::instance();;
                    \FedaPay\Requestor::setHttpClient($curlClient);
                    return $curlClient->request($method, $absUrl, $headers, $params);
                }
            ));
    }

    /**
     * Sets up a request expectation with the provided parameters. The request
     * will not actually be emitted, instead the provided response parameters
     * will be returned.
     *
     * @param string $method HTTP method (e.g. 'post', 'get', etc.)
     * @param string $path relative path (e.g. '/v1/transactions')
     * @param array|null $params array of parameters. If null, parameters will
     *   not be checked.
     * @param string[]|null $headers array of headers. Does not need to be
     *   exhaustive. If null, headers are not checked.
     * @param array $response
     * @param integer $rcode
     * @param string|null $base
     *
     * @return array
     */
    protected function stubRequest(
        $method,
        $path,
        $params = null,
        $headers = null,
        $response = [],
        $rcode = 200,
        $base = null
    ) {
        $this->prepareRequestMock($method, $path, $params, $headers, $base)
            ->willReturn([json_encode($response), $rcode, []]);
    }

    /**
     * Prepares the client mocker for an invocation of the `request` method.
     * This helper method is used by both `expectsRequest` and `stubRequest` to
     * prepare the client mocker to expect an invocation of the `request` method
     * with the provided arguments.
     *
     * @param string $method HTTP method (e.g. 'post', 'get', etc.)
     * @param string $path relative path (e.g. '/v1/transactions')
     * @param array|null $params array of parameters. If null, parameters will
     *   not be checked.
     * @param string[]|null $headers array of headers. Does not need to be
     *   exhaustive. If null, headers are not checked.
     * @param string|null $base base URL (e.g. 'https://api.fedapay.com')
     *
     * @return PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    private function prepareRequestMock(
        $method,
        $path,
        $params = null,
        $headers = null,
        $base = null
    ) {
        \FedaPay\Requestor::setHttpClient($this->clientMock);

        if ($base === null) {
            $base = \FedaPay\FedaPay::getApiBase();
        }
        $absUrl = $base.$path;

        return $this->clientMock
            ->expects($this->once())
            ->method('request')
            ->with(
                strtolower($method),
                $absUrl,
                // for headers, we only check that all of the headers provided in $headers are
                // present in the list of headers of the actual request
                $headers === null ? $this->anything() : $this->callback(function ($array) use ($headers) {
                    foreach ($headers as $header) {
                        if (!in_array($header, $array)) {
                            return false;
                        }
                    }
                    return true;
                }),
                $params === null ? $this->anything() : $params
            );
    }
}
