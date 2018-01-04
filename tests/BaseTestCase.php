<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

abstract class BaseTestCase extends TestCase
{
    protected $client;

    const API_KEY = 'sk_test_1N41L2m5WYR84pd_CW_zVRuf';

    protected function setUp()
    {
        \Fedapay\Fedapay::setApiKey(self::API_KEY);
    }

    public function createMockResponse($responseData, $statusCode, $method, $uri)
    {
        $headers = ['Content-Type' => 'application/json'];
        $body = json_encode($responseData);

        $response = new Response($statusCode, $headers, $body);

        $mock = new MockHandler([
            $response
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $response = $client->request(
            $method, $uri,
            ['query' => ['api_key' => \Fedapay\Fedapay::getApiKey()]]
        );

        return $response;
    }
}
