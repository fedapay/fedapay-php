<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;

abstract class BaseTestCase extends TestCase
{
    protected $container;

    const API_KEY = 'sk_test_123';
    const OAUTH_TOKEN = 'oauth_test_token_123';

    protected function setUp()
    {
        \FedaPay\FedaPay::setApiKey(self::API_KEY);
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

    public function createMockClient($status, $body = null, $headers = [])
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

    public function exceptRequest($path, $method)
    {
        // Iterate over the requests and responses
        foreach ($this->container as $transaction) {
            $request = $transaction['request'];

            $this->assertEquals($request->getUri()->getPath(), $path);
            $this->assertEquals($request->getMethod(), $method);
        }
    }
}
