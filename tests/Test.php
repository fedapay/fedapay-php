<?php

namespace Fedapay;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class Test {
  protected $client;
  const API_KEY = 'sk_test_yVr4E9WAib5-4rmKIyBEKpPe';

  protected function setUp()
    {

        $apiKey = getenv('FEDAPAY_API_KEY');
        if (!$apiKey) {
            $apiKey = self::API_KEY;
        }

        Fedapay::setApiKey($apiKey);
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

        $response = $client->request($method, $uri,
                            ['query' => ['api_key' => Fedapay::getApiKey()]]);

        return $response;
    }

}
