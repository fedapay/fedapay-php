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

    const API_KEY = 'sk_test_EzjPbqCO47Y4FY1h5VoKl7Qm';

    protected function setUp()
    {
        \Fedapay\Fedapay::setApiKey(self::API_KEY);
    }

    public function createMockClient($status, $headers = [], $body = [])
    {
        $body = json_encode($body);
        $response = new Response($status, $headers, $body);

        $mock = new MockHandler([$response]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return $client;
    }
}
