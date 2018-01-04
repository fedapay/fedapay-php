<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class TransactionTest extends BaseTestCase
{
    public function testGetAllTransactions()
    {
        $responseData = array('reference' => '1512322613531',
                         'description' => 'Laudantium est sequi ut quam.',
                         'callback_url' => 'http://rogahn.io/kaya.mitchell',
                         'amount' => '38758',
                         'status' => 'pending',
                         'items' => '1',
                         'customer_id' => '1'
                      );
        $statusCode = 200;
        $method = 'GET';
        $uri = '/v1/transactions';

        $response = Test::createMockResponse($responseData, $statusCode, $method, $uri);
        $data = json_decode($response->getBody(), true);
        $this->assertEquals('pending', $data['status']);
        $this->assertSame($responseData, $data);
        $this->assertTrue($response->getStatusCode() == $statusCode);

    }

    public function testOneTransaction()
    {
        $responseData = array('reference' => '1512322613531',
                           'description' => 'Laudantium est sequi ut quam.',
                           'callback_url' => 'http://rogahn.io/kaya.mitchell',
                           'amount' => '38758',
                           'status' => 'pending',
                           'items' => '1',
                           'customer_id' => '1'
                        );
        $statusCode = 200;
        $method = 'GET';
        $uri = '/v1/transactions/1';

        $response = Test::createMockResponse($responseData, $statusCode, $method, $uri);

        $this->assertEquals($statusCode, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertEquals('1', $data['items']);
    }
}
