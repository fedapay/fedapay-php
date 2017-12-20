<?php

namespace Fedapay;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class CustomerTest extends TestCase
{
  protected $client;
  const API_KEY = 'sk_test_yVr4E9WAib5-4rmKIyBEKpPe';

  protected function setUp()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.fedapay.com'
        ]);
        $apiKey = getenv('FEDAPAY_API_KEY');
        if (!$apiKey) {
            $apiKey = self::API_KEY;
        }

        Fedapay::setApiKey($apiKey);
    }

  public function testGetCustomerObject()
  {
      $response = $this->client->get('/v1/customers', [
          'query' => [
              'api_key' => self::API_KEY
          ]
      ]);

      $this->assertEquals(200, $response->getStatusCode());

      $data = json_decode($response->getBody(), true);

      // $this->assertArrayHasKey('bookId', $data);
      // $this->assertArrayHasKey('title', $data);
      // $this->assertArrayHasKey('author', $data);
      // $this->assertEquals(42, $data['price']);
  }

    public function testPostNewCustomerObject()
    {
      //$bookId = uniqid();

      $response = $this->client->post('/v1/customers', array(
        'query' => ['api_key' => self::API_KEY],
         'json' => [
            'firstname' => 'toto',
            'lastname' => 'zoro',
            'email' => 'admin@gmail.com',
            'phone' => '66666666'
         ]
      ));

      $this->assertEquals(200, $response->getStatusCode());

      $data = json_decode($response->getBody(), true);

      $this->assertEquals('toto', $data['firstname']);
    }

    public function testDelete_Error()
    {
      $customerId = uniqid();
      $response = $this->client->delete('/v1/customers/'+$customerId+'');

      $this->assertEquals(405, $response->getStatusCode());
    }
}
