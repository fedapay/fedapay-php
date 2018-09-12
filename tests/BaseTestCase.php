<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use FedaPay\HttpClient\CurlClient;

abstract class BaseTestCase extends TestCase
{
    protected $container;
    protected $mock;
    protected $call;
    protected $clientMock;

    const API_KEY = 'sk_local_123';
    const OAUTH_TOKEN = 'oauth_test_token_123';
    const API_BASE = 'https://dev-api.fedapay.com';

    protected function setUp()
    {
        \FedaPay\FedaPay::setApiKey(self::API_KEY);
        \FedaPay\FedaPay::setApiBase(self::API_BASE);

         \FedaPay\Requestor::setHttpClient(\FedaPay\HttpClient\CurlClient::instance());
        $this->mock = null;
        $this->call = 0;
         // Set up the HTTP client mocker
        // $this->clientMock = $this->getMock('\FedaPay\HttpClient\ClientInterface');
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

    protected function mockRequest($method, $path, $params = array(), $response = array(), $rcode = 200)
    {
        $mock = $this->setUpMockRequest();
        $base = \FedaPay\FedaPay::getApiBase();
        $absUrl = $base.$path;
        $mock->expects($this->at($this->call++))
             ->method('request')
            // ->with(strtolower($method), $absUrl, $this->anything(), $params)
             ->willReturn(array(json_encode($response), $rcode));
    }
    private function setUpMockRequest()
    {
        if (!$this->mock) {
            $this->mock = $this->getMockBuilder('\FedaPay\HttpClient\ClientInterface')
                                            ->setMethods(['request'])
                                            ->getMock();

            \FedaPay\Requestor::setHttpClient($this->mock);
        }
        return $this->mock;
    }
}
