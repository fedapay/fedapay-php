<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Fedapay\Test;

class CustomerTest extends BaseTestCase
{
    protected $client;

    public function testGetAllCustomers()
    {
        \Fedapay\Customer::all();
    }
    //
    // public function testCreateNewCustomer()
    // {
    //     $responseData = array('firstname' => 'toto',
    //                        'lastname' => 'zoro',
    //                        'email' => 'admin@gmail.com',
    //                        'phone' => '66666666'
    //                      );
    //     $statusCode = 200;
    //     $method = 'POST';
    //     $uri = '/v1/customers';
    //
    //     $response = Test::createMockResponse($responseData, $statusCode, $method, $uri);
    //
    //     $this->assertEquals($statusCode, $response->getStatusCode());
    //
    //     $data = json_decode($response->getBody(), true);
    //
    //     $this->assertEquals('toto', $data['firstname']);
    // }
}
