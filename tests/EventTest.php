<?php

namespace Fedapay;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Fedapay\Test;

class EventTest extends TestCase
{

  public function testGetAllEvents()
  {
    $responseData = (array('type' =>  'customer.created',
                          'object' =>  'customer',
                      ));
    $statusCode = 200;
    $method = 'GET';
    $uri = '/v1/events';

    $response = Test::createMockResponse($responseData, $statusCode, $method, $uri);
    $data = json_decode($response->getBody(), true);
    $this->assertEquals('customer', $data['object']);
    $this->assertSame($responseData, $data);
    $this->assertTrue($response->getStatusCode() == $statusCode);

  }

    public function testOneTransaction()
    {
      $responseData = (array('type' =>  'customer.created',
                            'object' =>  'customer',
                        ));
      $statusCode = 200;
      $method = 'GET';
      $uri = '/v1/events/1';

      $response = Test::createMockResponse($responseData, $statusCode, $method, $uri);

      $this->assertEquals($statusCode, $response->getStatusCode());
      $data = json_decode($response->getBody(), true);
      $this->assertEquals('customer.created', $data['type']);
    }

}
