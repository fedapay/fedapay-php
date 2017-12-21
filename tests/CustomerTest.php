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

class CustomerTest extends TestCase
{
  protected $client;
  const API_KEY = 'sk_test_yVr4E9WAib5-4rmKIyBEKpPe';

  public function testGetAllCustomers()
  {
    $responseData = (array('firstname' => 'toto',
                         'lastname' => 'zoro',
                         'email' => 'admin@gmail.com',
                         'phone' => '66666666'
                       ));
    $statusCode = 200;
    $method = 'GET';
    $uri = '/v1/customers';

    $response = Test::createMockResponse($responseData, $statusCode, $method, $uri);
    $data = json_decode($response->getBody(), true);
    $this->assertEquals('zoro', $data['lastname']);
    $this->assertSame($responseData, $data);
    $this->assertTrue($response->getStatusCode() == $statusCode);

  }

    public function testCreateNewCustomer()
    {
      $responseData = (array('firstname' => 'toto',
                           'lastname' => 'zoro',
                           'email' => 'admin@gmail.com',
                           'phone' => '66666666'
                         ));
      $statusCode = 200;
      $method = 'POST';
      $uri = '/v1/customers';

      $response = Test::createMockResponse($responseData, $statusCode, $method, $uri);

      $this->assertEquals($statusCode, $response->getStatusCode());

      $data = json_decode($response->getBody(), true);

      $this->assertEquals('toto', $data['firstname']);
    }

}
